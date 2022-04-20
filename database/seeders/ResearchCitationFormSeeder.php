<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ResearchField;

class ResearchCitationFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Research Code',
            'name' => 'research_code',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 2,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Title of Article Where Your Research has been cited',
            'name' => 'article_title',
            'placeholder' => null,
            'size' => 'col-md-9',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Authors/s Who Cited Your Research',
            'name' => 'article_author',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Title of the Journal/ Book Where Your Article has been cited',
            'name' => 'journal_title',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Name of the Publisher',
            'name' => 'journal_publisher',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Volume No.',
            'name' => 'volume',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Issue No.',
            'name' => 'issue',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Page No.',
            'name' => 'page',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Year',
            'name' => 'year',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Indexing Platform',
            'name' => 'indexing_platform',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 9, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => "*Print Screen from Goole Scholar/Internet indicating the title of papers citing the paper, *Reference Page highlighting the name of authors and title of research cited, *Pages of books highlighting the citation",
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 5,
            'label' => 'Document Upload',
            'name' => 'document',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 10,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
    }
}
