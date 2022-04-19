<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentDescription;

class DocumentDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Research Regi
        DocumentDescription::truncate();
        DocumentDescription::insert([
            'report_category_id' => 1,
            'name' => 'MOA',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 1,
            'name' => 'MOU',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 1,
            'name' => 'BOR Resolution',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 1,
            'name' => 'Abstract',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 1,
            'name' => 'Research Proposal',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 1,
            'name' => 'Certification Issued by the Reviewer of the Research Instrument Used',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 1,
            'name' => 'Ethics Clearance',
            'is_active' => 1,
        ]);

        //Research Completed
        DocumentDescription::insert([
            'report_category_id' => 2,
            'name' => 'Abstract',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 2,
            'name' => 'Draft Manuscript',
            'is_active' => 1,
        ]);

        //Research Publication
        DocumentDescription::insert([
            'report_category_id' => 3,
            'name' => 'Actual Page(s) with Journal Details',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 3,
            'name' => 'Journal Title Page and Table of Contents',
            'is_active' => 1,
        ]);

        //Research Presentation
        DocumentDescription::insert([
            'report_category_id' => 4,
            'name' => 'Abstract',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 4,
            'name' => 'Certificate of Presentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 4,
            'name' => 'Certificate of Attendance',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 4,
            'name' => 'Conference Proceedings',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 4,
            'name' => 'Documentation of Paper Presentation',
            'is_active' => 1,
        ]);

        //Research Citation
        DocumentDescription::insert([
            'report_category_id' => 5,
            'name' => 'Print Screen from Goole Scholar/Internet indicating the title of papers citing the paper',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 5,
            'name' => 'Reference Page highlighting the name of authors and title of research cited',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 5,
            'name' => 'Pages of books highlighting the citation',
            'is_active' => 1,
        ]);

        //Research Utilization
        DocumentDescription::insert([
            'report_category_id' => 6,
            'name' => 'Certification from the industry/beneficiary who utilized the research',
            'is_active' => 1,
        ]);

        //Research Copyrighted
        DocumentDescription::insert([
            'report_category_id' => 7,
            'name' => 'Copy of Copyright Application',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 7,
            'name' => 'Copy of Approved Copypaper',
            'is_active' => 1,
        ]);

        //Invention
        DocumentDescription::insert([
            'report_category_id' => 8,
            'name' => 'Certificate of Patent',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 8,
            'name' => 'Full Paper of Invention',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 8,
            'name' => 'Certificate of Manufacturers/Fabricators',
            'is_active' => 1,
        ]);

        //Expert Service as Consultant
        DocumentDescription::insert([
            'report_category_id' => 9,
            'name' => 'Special Order',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 9,
            'name' => 'MOU',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 9,
            'name' => 'MOA',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 9,
            'name' => 'Citation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 9,
            'name' => 'Certificate',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 9,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 9,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);

        //Expert Service rendered in Conference
        DocumentDescription::insert([
            'report_category_id' => 10,
            'name' => 'Special Order',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 10,
            'name' => 'MOU',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 10,
            'name' => 'MOA',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 10,
            'name' => 'Citation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 10,
            'name' => 'Certificate',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 10,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 10,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);

        //Expert Service Rendered in Academic
        DocumentDescription::insert([
            'report_category_id' => 11,
            'name' => 'Special Order',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 11,
            'name' => 'MOU',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 11,
            'name' => 'MOA',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 11,
            'name' => 'Citation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 11,
            'name' => 'Certificate',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 11,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 11,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);

        //Extension Program/Service
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'Special Order',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'Extension Project Proposal',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'Extension Program Write-up/Terminal Report',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'MOA',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'MOU',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'Citation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'Certificate',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'Survey/Evaluation Instrument Used',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 12,
            'name' => 'Summary Report of Evaluation Result',
            'is_active' => 1,
        ]);

        //Partnership
        DocumentDescription::insert([
            'report_category_id' => 13,
            'name' => 'Special Order',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 13,
            'name' => 'Budget Allocation & Utilization',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 13,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 13,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 13,
            'name' => 'MOA',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 13,
            'name' => 'MOU',
            'is_active' => 1,
        ]);

        //Mobility
        DocumentDescription::insert([
            'report_category_id' => 14,
            'name' => 'Special Order',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 14,
            'name' => 'Proof of enrollment in the SUC',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 14,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 14,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 14,
            'name' => 'MOA',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 14,
            'name' => 'MOU',
            'is_active' => 1,
        ]);

        //Student Awards
        DocumentDescription::insert([
            'report_category_id' => 18,
            'name' => 'Citation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 18,
            'name' => 'Certificate of Award',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 18,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 18,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);

        //Student Training
        DocumentDescription::insert([
            'report_category_id' => 19,
            'name' => 'Certificate',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 19,
            'name' => 'Terminal report',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 19,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 19,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);

        //Viable demo project
        DocumentDescription::insert([
            'report_category_id' => 20,
            'name' => 'Copy of audited financial statement',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 20,
            'name' => 'Copy of certification from VP For Administration and Finance',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 20,
            'name' => 'Computation of ROI for each project duly signed and certified true and correct by the SUC accountant and attested by the supervisor concerned',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 20,
            'name' => 'Computation of IRR for each project duly signed and certified true and correct by the SUC accountant and attested by the supervisor concerned',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 20,
            'name' => 'Certification of utilization in programs offered by the SUC',
            'is_active' => 1,
        ]);

        //awards or recognition received by CBCO
        DocumentDescription::insert([
            'report_category_id' => 21,
            'name' => 'Citation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 21,
            'name' => 'Certificate of Award',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 21,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 21,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);

        //Outreach Program
        DocumentDescription::insert([
            'report_category_id' => 22,
            'name' => 'Special Order',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 22,
            'name' => 'Budget Allocation & Utilization',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 22,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 22,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 22,
            'name' => 'Citation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 22,
            'name' => 'Certificate',
            'is_active' => 1,
        ]);

        // Attendance in Relevant Faculty/Admin Development Program (Seminars/ Webinars, Fora/Conferences) and Trainings
        for ($i = 25; $i <= 26; $i++) {
            DocumentDescription::insert([
                'report_category_id' => $i,
                'name' => 'Special Order',
                'is_active' => 1,
            ]);
            DocumentDescription::insert([
                'report_category_id' => $i,
                'name' => 'Certificate',
                'is_active' => 1,
            ]);
            DocumentDescription::insert([
                'report_category_id' => $i,
                'name' => 'Terminal Report',
                'is_active' => 1,
            ]);
            DocumentDescription::insert([
                'report_category_id' => $i,
                'name' => 'Special Order',
                'is_active' => 1,
            ]);
        }
        // Outstanding Awards
        DocumentDescription::insert([
            'report_category_id' => 27,
            'name' => 'Citation',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 27,
            'name' => 'Certificate of Award',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 27,
            'name' => 'Pictures',
            'is_active' => 1,
        ]);
        DocumentDescription::insert([
            'report_category_id' => 27,
            'name' => 'Documentation',
            'is_active' => 1,
        ]);

        // Officership
        DocumentDescription::insert([
            'report_category_id' => 28,
            'name' => 'Certificate',
            'is_active' => 1,
        ]);
    }
}
