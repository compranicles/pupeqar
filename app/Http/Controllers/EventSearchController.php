<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Event;
use App\Models\Invite;
use App\Models\EventType;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Finder\SplFileInfo;
use App\Http\Resources\SiteSearchResource;

class EventSearchController extends Controller
{

    const BUFFER = 10;

    private function modelNamespacePrefix(){
        return app()->getNamespace() . 'Models\\';
    }

    public function search(Request $request){
        
        $keyword = $request->search;

        $toExclude = [];

        $files =  File::allFiles(app()->basePath().'/app/Models');

        $results = collect($files)->map(function (SplFileInfo $file){
            $filename = $file->getRelativePathname();
            if(substr($filename, -4) !== '.php'){
                return null;
            }
            return substr($filename, 0, -4);
        })->filter(function (?string $classname) use($toExclude){
            if($classname === null){
                return false;
            }
            $reflection = new \ReflectionClass($this->modelNamespacePrefix().$classname);
            $isModel = $reflection->isSubclassOf(Model::class);

            $searchable = $reflection->hasMethod('search');

            return $isModel && $searchable && !in_array($reflection->getName(), $toExclude, true);
        })->map(function ($classname) use($keyword){
            $model = app($this->modelNamespacePrefix(). $classname);
            return $model::search($keyword)->take(10)->get()->map(function ($modelRecord) use($model, $keyword, $classname){
                $fields = array_filter($model::SEARCHABLE_FIELDS, fn($field) => $field !== 'id');
                
                $fieldsData = $modelRecord->only($fields);

                $serializedValues = collect($fieldsData)->join(' ');

                $searchPos = strpos(strtolower($serializedValues), strtolower($keyword));

                if($searchPos !== false){
                    $start = $searchPos - self::BUFFER;
                    $start = $start < 0 ? 0 : $start; 
                    $length = strlen($keyword) + 2 * self::BUFFER;
                    $sliced = substr($serializedValues, $start, $length);
                    $shouldAddPrefix = $start > 0;
                    $shouldAddPostfix = ($start + $length) < strlen($serializedValues);
                    $sliced = $shouldAddPrefix ? '...' . $sliced : $sliced;
                    $sliced = $shouldAddPrefix ? $sliced . '...' : $sliced;

                }

                $modelRecord->setAttribute('match', $sliced ?? substr($serializedValues, 0, 2 * self::BUFFER). '...');
                $modelRecord->setAttribute('model', $classname);
                $modelRecord->setAttribute('view-link', $this->resolveModelViewLink($modelRecord));
                return $modelRecord;
            });
        });

        dd($results);
    }

    private function resolveModelViewLink(Model $model){
        $mapping = [
            \App\Models\Event::class => '/events/view/{id}'
        ];
        $modelClass = get_class($model);

        

        $modelName = Str::plural(Arr::last(explode('\\', $modelClass)));
        $modelName = Str::kebab(Str::camel($modelName));

        if(Arr::has($mapping, $modelClass)){
            return str_replace('{id}', $model->id, $mapping[$modelClass]);
        }

        return URL::to('/'. strtolower($modelName).'/'.$model->id);

    }
}
