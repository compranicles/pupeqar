<?php

namespace App\Services;

use App\Models\HRISDocument;
use Intervention\Image\Facades\Image;

class SavePersonalDataDocumentService {
    public function saveFilesFromPersonnelPortal($seminar, $hrisFormId, $id) {
        $filenames = [];
        $imagejpeg = ['image/jpeg', 'image/pjpeg', 'image/jpg', 'image/jfif', 'image/pjp'];
        if ($hrisFormId == 4)
            $reportName = "ADP";
        elseif ($hrisFormId == 5)
            $reportName == "TRA";

        if (isset($seminar->MimeTypeSO)) {
            if(in_array($seminar->MimeTypeSO, $imagejpeg)){
                $fileSO = Image::make($seminar->AttachmentSO);
                $fileName = 'HRIS-'.$reportName.'-SO-'.now()->timestamp.uniqid().'.jpeg';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $fileSO->save($newPath);
            }
            elseif($seminar->MimeTypeSO == 'image/png' || $seminar->MimeTypeSO == 'image/x-png'){
                $fileSO = Image::make($seminar->AttachmentSO);
                $fileName = 'HRIS-'.$reportName.'-SO-'.now()->timestamp.uniqid().'.png';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $fileSO->save($newPath);
            }
            elseif($seminar->MimeTypeSO == 'application/pdf'){
                $fileName = 'HRIS-'.$reportName.'-SO-'.now()->timestamp.uniqid().'.pdf';
                file_put_contents(storage_path().'/app/documents/'.$fileName, $seminar->AttachmentSO);
                $fileSO = true;
            }
            if(isset($fileSO)){
                HRISDocument::create([
                    'hris_form_id' => $hrisFormId,
                    'reference_id' => $id,
                    'filename' => $fileName,
                ]);
                array_push($filenames, $fileName);
            }
            else{
                return false;
            }
        }

        if (isset($seminar->MimeTypeCert)) {
            if(in_array($seminar->MimeTypeCert, $imagejpeg)){
                $fileCert = Image::make($seminar->AttachmentCert);
                $fileName = 'HRIS-'.$reportName.'-CERT-'.now()->timestamp.uniqid().'.jpeg';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $fileCert->save($newPath);
            }
            elseif($seminar->MimeTypeCert == 'image/png' || $seminar->MimeTypeCert == 'image/x-png'){
                $fileCert = Image::make($seminar->AttachmentCert);
                $fileName = 'HRIS-'.$reportName.'-CERT-'.now()->timestamp.uniqid().'.png';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $fileCert->save($newPath);
            }
            elseif($seminar->MimeTypeCert == 'application/pdf'){
                $fileName = 'HRIS-'.$reportName.'-CERT-'.now()->timestamp.uniqid().'.pdf';
                file_put_contents(storage_path().'/app/documents/'.$fileName, $seminar->AttachmentCert);
                $fileCert = true;
            }
            if(isset($fileCert)){
                HRISDocument::create([
                    'hris_form_id' => $hrisFormId,
                    'reference_id' => $id,
                    'filename' => $fileName,
                ]);
                array_push($filenames, $fileName);
            }
            else{
                return false;
            }
        }

        if (isset($seminar->MimeTypePic)) {
            if(in_array($seminar->MimeTypePic, $imagejpeg)){
                $filePic = Image::make($seminar->AttachmentPic);
                $fileName = 'HRIS-'.$reportName.'-PICS-'.now()->timestamp.uniqid().'.jpeg';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $filePic->save($newPath);
            }
            elseif($seminar->MimeTypePic == 'image/png' || $seminar->MimeTypePic == 'image/x-png'){
                $filePic = Image::make($seminar->AttachmentPic);
                $fileName = 'HRIS-'.$reportName.'-PICS-'.now()->timestamp.uniqid().'.png';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $filePic->save($newPath);
            }
            elseif($seminar->MimeTypePic == 'application/pdf'){
                $fileName = 'HRIS-'.$reportName.'-PICS-'.now()->timestamp.uniqid().'.pdf';
                file_put_contents(storage_path().'/app/documents/'.$fileName, $seminar->AttachmentPic);
                $filePic = true;
            }
            if(isset($filePic)){
                HRISDocument::create([
                    'hris_form_id' => $hrisFormId,
                    'reference_id' => $id,
                    'filename' => $fileName,
                ]);
                array_push($filenames, $fileName);
            }
            else{
                return false;
            }
        }

        return $filenames;
    }
}