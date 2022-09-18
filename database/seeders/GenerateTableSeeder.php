<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\GenerateTable;

class GenerateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GenerateTable::truncate();

        //ADMIN - Type_id = 1
        GenerateTable::insert([
            'name' => 'I. ACCOMPLISHMENT REPORT',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'A. ONGOING/ADVANCED PROFESSIONAL STUDY',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 24,
            'footers' => json_encode([
                '* Level I, II, III, IV, COD, COE, Top 1000 University Ranking',
                '** Financial Aid, Scholarship, Tuition Fee Discount, Self-Funded',
                '***Currently Enrolled (New Student), Currently Enrolled (Old Student), Leave of Absence,  Completed Academic Requirement, Passed Comprehensive Exam, Currently Enrolled for Thesis Writing, Currently Enrolled for Dissertation Writing'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'B. OUTSTANDING ACHIEVEMENTS/ AWARDS, OFFICERSHIP/MEMBERSHIP IN PROFESSIONAL ORGANIZATION/S, & TRAININGS/ SEMINARS ATTENDED',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'B.1. Administrative Employees Outstanding Achievements/Awards',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 27,
            'footers' => json_encode([
                '* Research, Extension, Arts/Media/Culture & Sports,Invention,Innovation,Professional, Service',
                '**  International,National, Regional, Provincial/City/Municipal, Local-PUP',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'B.2. Officership/ Membership in Professional Organization/s',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 28,
            'footers' => json_encode([
                '* Learned, Honor, Scientific, Professional, GAD Related',
                '**  International,National, Regional, Provincial/City/Municipal, Local-PUP',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'B.3.1 Attendance in Relevant Administrative Employee Development Program (Seminars/ Webinars, Fora/Conferences)',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 25,
            'footers' => json_encode([
                '* Seminar/Webinar, Fora, Conference, Planning',
                '** GAD Related, Inclusivity and Diversity, Professional, Skills/Technical',
                '*** University Funded, Self-Funded, Externally Funded',
                '****  International,National, Regional, Provincial/City/Municipal, Local-PUP'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'B.3.2. Attendance in Training/s',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 26,
            'footers' => json_encode([
                '* Workshop, Professional/Continuing Professional Development, Short Term Courses, Executive/Managerial',
                '** GAD Related, Inclusivity and Diversity, Professional, Skills/Technical',
                '*** University Funded, Self-Funded, Externally Funded',
                '****  International,National, Regional, Provincial/City/Municipal, Local-PUP'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'II. ACCOMPLISHMENTS BASED ON OPCR',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 30,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => '',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 31,
            'footers' => null,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => '',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 32,
            'footers' => null,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => 'III. ATTENDANCE IN UNIVERSITY AND OFFICE FUNCTIONS',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 33,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'IV. REQUESTS AND QUERIES ACTED UPON',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 17,
            'footers' => json_encode([
                '* Simple, Complex, Highly Technical'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'V. SPECIAL TASKS',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 29,
            'footers' => json_encode([
                '* International, National, Local',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'VI. OTHER ACCOMPLISHMENTS (IF ANY)',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'A. RESEARCH & BOOK CHAPTER (PRODUCTION, CITATION, PRESENTATION)',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'A.1. Research Ongoing /Completed',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 1,
            'footers' => json_encode([
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '*****Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'A.2. Research Publication',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 3,
            'footers' => json_encode([
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '*****Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'A.3. Research Presentation',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 4,
            'footers' => json_encode([
                '* Program, Project, Study',
                '** Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '***** Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
                '******* International, National, Local'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'A.4. Research Citation',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 5,
            'footers' => json_encode([
                '* Program, Project, Study',
                '** Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '*****Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
                '******* Scopus, Web of Science, OASUC Accredited Journals, CHED Recognized Journals, International Refereed Journals, Excellence in Research for Australia, and ASEAN Citation Index'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'A.5. Research Utilization',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 6,
            'footers' => json_encode([
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '*****Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
                '******* International, National, Regional, Local'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'A.6. Copyrighted Research Output',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 7,
            'footers' => json_encode([
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '*****Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
                '******* Scopus, Web of Science, OASUC Accredited Journals, CHED Recognized Journals, International Refereed Journals, Excellence in Research for Australia, and ASEAN Citation Index'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'B. Inventions, Innovation, and Creative Works',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'B.1 Administrative Employee Invention, Innovation and Creative Works Commitment',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 8,
            'footers' => json_encode([
                '*Invention, Innovation, Creative Works',
                '** Completed, Ongoing, Deferred',
                '*** University Funded, Externally Funded, Self-Funded',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C. Extension Program and Expert Service Rendered',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null,
        ]);
        GenerateTable::insert([
            'name' => 'C.1. Expert Service Rendered',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 9,
            'footers' => json_encode([
                '*Education, Technology, Arts & Sports, Professional/Scientific, Organizational Development/Management',
                '** International,National, Regional, Provincial/City/Municipal, Local-PUP',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => '',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 10,
            'footers' => json_encode([
                '* Trainer, Coordinator, Lecturer, Resource/Guest Speaker',
                '** International,National, Regional, Provincial/City/Municipal, Local-PUP'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => '',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 11,
            'footers' => json_encode([
                '* Academic Journal, Books/ Publication, Newsletter, Creative Works',
                '** Member, Officer, Consultant, Reviewer, Editor, Technical Panel, Coordinator, Others',
                '*** Scopus, Web of Science, OASUC Accredited Journals, CHED Recognized Journals, International Refereed Journals, Excellence in Research for Australia, and ASEAN Citation Index',
                '**** International,National, Regional, Provincial/City/Municipal, Local-PUP'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C.2. Extension Program, Project and Activity (Ongoing and Completed)',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 12,
            'footers' => json_encode([
                '* Facilitator, Resource Speaker, Organizer,Extensionist',
                '** University Funded, Self-Funded, Externally Funded',
                '***Livelihood Development,Health,Educational and Cultural Exchange,Technology Transfer,Knowledge Transfer,Local Governance,IT,Others',
                '**** International,National, Regional, Provincial/City/Municipal, Local-PUP',
                '***** Completed, Ongoing, Deferred',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C.3. Partnership/Linkages/Network',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 13,
            'footers' => json_encode([
                '*BPO,Educational Institution,Food Service,Government Agency,Hotel and Hospitality Service,Medical Service,NGO,Professional Organization,Telecommunication,Travel Agency,Others',
                '**Facilitator, Resource Speaker, Organizer,Extensionist',
                '***Technology Transfer,Training/ Instruction conducted,Information, Education and Communication,Research ,Consultancy,Linkages,Network,Others,',
                '**** Administrative Employee, Community, Students, HEI Administrators, Others',
                '***** International,National, Regional, Provincial/City/Municipal, Local-PUP',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C.4. Involvement in Inter-Country Mobility',
            'is_table' => 1,
            'type_id' => 1,
            'report_category_id' => 14,
            'is_individual' => 1,
            'footers' => json_encode([
                '*Resource Person/Speaker/Panel,Institutional Representative,Country Representative,MOU/MOA Signing,Performer,Sports Delegates,Visiting Professor,',
                '**On the Job Training, Internship, Exchange Student, Others'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C.5. Involvement in Intra-Country Mobility',
            'is_table' => 1,
            'type_id' => 1,
            'report_category_id' => 34,
            'is_individual' => 1,
            'footers' => json_encode([
                '*Resource Person/Speaker/Panel,Institutional Representative,Country Representative,MOU/MOA Signing,Performer,Sports Delegates,Visiting Professor,',
                '**On the Job Training, Internship, Exchange Student, Others'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'D. Other Accomplishments Beyond the Mandatory Requirements',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => 38,
        ]);
        GenerateTable::insert([
            'name' => 'A. Technical Extension Programs/Projects/Activities',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 23,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => 'B. Community Engagement Conducted by Section/Office',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 37,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => 'C. Viable Demonstration Projects',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 20,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => 'D. Awards/Recognitions Received by the Office from Reputable Organizations',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 21,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => 'E. Community Relations and Outreach Programs',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 22,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => 'F. Intercountry Mobility',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 35,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => 'G. Intra-Country Mobility',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 36,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => 'H. Other Accomplishments',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 39,
            'deleted_at' => NOW()
        ]);

        //Academic
        GenerateTable::insert([
            'name' => 'I. ACCOMPLISHMENT REPORT',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'A. ONGOING/ADVANCED PROFESSIONAL STUDY',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 24,
            'footers' => json_encode( [
                '* Level I, II, III, IV, COD, COE, Top 1000 University Ranking',
                '** Financial Aid, Scholarship, Tuition Fee Discount, Self-Funded',
                '***Currently Enrolled (New Student), Currently Enrolled (Old Student), Leave of Absence,  Completed Academic Requirement, Passed Comprehensive Exam, Currently Enrolled for Thesis Writing, Currently Enrolled for Dissertation Writing'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'B. OUTSTANDING ACHIEVEMENTS/ AWARDS, OFFICERSHIP/MEMBERSHIP IN PROFESSIONAL ORGANIZATION/S, & TRAININGS/ SEMINARS ATTENDED',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'B.1. Faculty Outstanding Achievements/Awards',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 27,
            'footers' => json_encode([
                '* Research, Extension, Arts/Media/Culture & Sports,Invention,Innovation,Professional, Service',
                '**  International,National, Regional, Provincial/City/Municipal, Local-PUP',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'B.2. Officership/ Membership in Professional Organization/s',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 28,
            'footers' => json_encode([
                '* Learned, Honor, Scientific, Professional, GAD Related',
                '**  International,National, Regional, Provincial/City/Municipal, Local-PUP',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'B.3.1 Attendance in Relevant Faculty Development Program (Seminars/ Webinars, Fora/Conferences)',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 25,
            'footers' => json_encode([
                '* Seminar/Webinar, Fora, Conference, Planning',
                '** GAD Related, Inclusivity and Diversity, Professional, Skills/Technical',
                '*** University Funded, Self-Funded, Externally Funded',
                '****  International,National, Regional, Provincial/City/Municipal, Local-PUP'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'B.3.2. Attendance in Training/s',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 26,
            'footers' => json_encode([
                '* Workshop, Professional/Continuing Professional Development, Short Term Courses, Executive/Managerial',
                '** GAD Related, Inclusivity and Diversity, Professional, Skills/Technical',
                '*** University Funded, Self-Funded, Externally Funded',
                '****  International,National, Regional, Provincial/City/Municipal, Local-PUP'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C. Research & Book Chapter (Production, Citation, Presentation)',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'C.1. Research Ongoing /Completed',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 1,
            'footers' => json_encode([
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '***** Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C.2. Research Publication',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 3,
            'footers' => json_encode( [
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '***** Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C.3. Research Presentation',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 4,
            'footers' => json_encode([
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '***** Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
                '******* International, National, Local'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C.4. Research Citation',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 5,
            'footers' => json_encode([
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '***** Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
                '******* Scopus, Web of Science, OASUC Accredited Journals, CHED Recognized Journals, International Refereed Journals, Excellence in Research for Australia, and ASEAN Citation Index'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C.5. Research Utilization',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 6,
            'footers' => json_encode([
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '***** Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
                '******* International, National, Regional, Local'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'C.6. Copyrighted Research Output',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 7,
            'footers' => json_encode([
                '* Program, Project, Study',
                '**Research, Book Chapter',
                '***  Poverty Reduction, Peace and Security, Accelerating Infrastructure Development through Science and Technology,  Competitive Industry and Entrepreneurship, Environmental Conservation, Protection and Rehabilitation towards Sustainable Development',
                '**** Independent Researcher, Lead Researcher, Co-Lead Researcher, Associate Lead Researcher',
                '***** Basic Research (GAD Related),Basic Research (Diversity and Inclusivity Related),Basic Research (GAD Related & Diversity and Inclusivity Related),Applied Research (GAD Related),Applied Research (Diversity and Inclusivity Related),Applied Research (GAD Related & Diversity and Inclusivity Related),Basic Research,Applied Research,Creative Work',
                '****** Externally Funded, University Funded, Self Funded',
                '******* Scopus, Web of Science, OASUC Accredited Journals, CHED Recognized Journals, International Refereed Journals, Excellence in Research for Australia, and ASEAN Citation Index'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'D. Faculty Inventions, Innovation, and Creative Works',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'D.1 Faculty Invention, Innovation and Creative Works Commitment',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 8,
            'footers' => json_encode([
                '*Invention, Innovation, Creative Works',
                '** Completed, Ongoing, Deferred',
                '*** University Funded, Externally Funded, Self-Funded',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'E. Extension Program and Expert Service Rendered',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'E.1. Expert Service Rendered',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 9,
            'footers' => json_encode([
               '*Education, Technology, Arts & Sports, Professional/Scientific, Organizational Development/Management',
               '** International,National, Regional, Provincial/City/Municipal, Local-PUP',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => '',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 10,
            'footers' => json_encode([
                '* Trainer, Coordinator, Lecturer, Resource/Guest Speaker',
                '** International,National, Regional, Provincial/City/Municipal, Local-PUP',
             ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => '',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 11,
            'footers' => json_encode([
                '* Academic Journal, Books/ Publication, Newsletter, Creative Works',
                '** Member, Officer, Consultant, Reviewer, Editor, Technical Panel, Coordinator, Others',
                '*** Scopus, Web of Science, PASUC Accredited Journals, CHED Recognized Journals, International Refereed Journals, Excellence in Research for Australia, and ASEAN Citation Index',
                '**** International,National, Regional, Provincial/City/Municipal, Local-PUP'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'E.2. Extension Program, Project and Activity (Ongoing and Completed)',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 12,
            'footers' => json_encode([
                '* Facilitator, Resource Speaker, Organizer,Extensionist',
                '** University Funded, Self-Funded, Externally Funded',
                '***Livelihood Development,Health,Educational and Cultural Exchange,Technology Transfer,Knowledge Transfer,Local Governance,IT,Others ,',
                '**** International,National, Regional, Provincial/City/Municipal, Local-PUP',
                '***** Completed, Ongoing, Deferred',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'E.3. Partnership/Linkages/Network',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 13,
            'footers' => json_encode([
                '*BPO,Educational Institution,Food Service,Government Agency,Hotel and Hospitality Service,Medical Service,NGO,Professional Organization,Telecommunication,Travel Agency,Others',
                '** Facilitator, Resource Speaker, Organizer,Extensionist',
                '***Technology Transfer,Training/ Instruction conducted,Information, Education and Communication,Research ,Consultancy,Linkages,Network,Others,',
                '**** Faculty, Community, Students, HEI Administrators, Others',
                '***** International,National, Regional, Provincial/City/Municipal, Local-PUP',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'E.4. Faculty Involvement in Inter-Country Mobility',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 14,
            'footers' => json_encode([
                '*Resource Person/Speaker/Panel,Institutional Representative,Country Representative,MOU/MOA Signing,Performer,Sports Delegates,Visiting Professor',
                '**On the Job Training, Internship, Exchange Student, Others'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'E.5. Faculty Involvement in Intra-Country Mobility',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 34,
            'footers' => json_encode([
                '*Resource Person/Speaker/Panel,Institutional Representative,Country Representative,MOU/MOA Signing,Performer,Sports Delegates,Visiting Professor',
                '**On the Job Training, Internship, Exchange Student, Others'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'F. Academic Program Development',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'F.1. Instructional Material, Reference/Text Book, Module, Monographs',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 15,
            'footers' => json_encode([
               '* International,National, Regional, Provincial/City/Municipal, Local-PUP'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::insert([
            'name' => 'F.2. Course Syllabus/ Guide Developed/Revised/Enhanced',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 16,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'II. SPECIAL TASKS',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 30,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => '',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 31,
            'footers' => null,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => '',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 32,
            'footers' => null,
            'deleted_at' => NOW()
        ]);
        GenerateTable::insert([
            'name' => 'III. ATTENDANCE IN UNIVERSITY AND COLLEGE FUNCTIONS',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 33,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'IV. Other Accomplishments Beyond the Mandatory Requirements',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 38,
        ]);
        GenerateTable::insert([
            'name' => 'V. Students Awards/ Recognitions from Reputable Organizations',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 0,
            'report_category_id' => 18,
        ]);
        GenerateTable::insert([
            'name' => 'VI. STUDENTS TRAININGS AND SEMINARS',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 0,
            'report_category_id' => 19,
            'footers' => json_encode([
                '* Seminar/Webinar, Fora, Conference, Training',
                '** GAD Related, Inclusivity and Diversity, Professional, Skills/Technical',
                '*** University Funded, Self Funded, Externally Funded, No Funding Required'
            ], JSON_FORCE_OBJECT)
        ]);

        //Department Wide Level Type ID = 3
        GenerateTable::insert([
            'name' => 'A. Technical Extension Programs/Projects/Activities',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 23,
        ]);
        GenerateTable::insert([
            'name' => 'B. Community Engagement Conducted by Department',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 37,
        ]);
        GenerateTable::insert([
            'name' => 'C. Viable Demonstration Projects',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 20,
        ]);
        GenerateTable::insert([
            'name' => 'D. Awards/Recognitions Received by the Department from Reputable Organizations',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 21,
        ]);
        GenerateTable::insert([
            'name' => 'E. Community Relations and Outreach Programs',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 22,
        ]);
        GenerateTable::insert([
            'name' => 'F. Intercountry Mobility',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 35,
        ]);
        GenerateTable::insert([
            'name' => 'G. Intra-Country Mobility',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 36,
        ]);
        GenerateTable::insert([
            'name' => 'H. Other Accomplishments',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 39,
        ]);
        /* College Wide Level Tables */
        GenerateTable::insert([
            'name' => 'COLLEGE LEVEL ACCOMPLISHMENTS (TO BE FILLED-IN BY DEAN)																	            ',
            'is_table' => 0,
            'type_id' => 4,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::insert([
            'name' => 'A. Technical Extension Programs/Projects/Activities',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 23,
        ]);
        GenerateTable::insert([
            'name' => 'B. Community Engagement Conducted by College',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 37,
        ]);
        GenerateTable::insert([
            'name' => 'C. Viable Demonstration Projects',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 20,
        ]);
        GenerateTable::insert([
            'name' => 'D. Awards/Recognitions Received by the College/Branch/Campus/Office from Reputable Organizations',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 21,
        ]);
        GenerateTable::insert([
            'name' => 'E. Community Relations and Outreach Programs',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 22,
        ]);
        GenerateTable::insert([
            'name' => 'F. Intercountry Mobility',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 35,
        ]);
        GenerateTable::insert([
            'name' => 'G. Intra-Country Mobility',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 36,
        ]);
        GenerateTable::insert([
            'name' => 'H. Other Accomplishments',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 39,
        ]);
    }
}
