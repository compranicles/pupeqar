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
        GenerateTable::create([
            'name' => 'I. ACCOMPLISHMENT REPORT',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'A. ONGOING ADVANCED/ PROFESSIONAL STUDY',
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
        GenerateTable::create([
            'name' => 'B. OUTSTANDING ACHIEVEMENTS/ AWARDS, OFFICERSHIP/MEMBERSHIP IN PROFESSIONAL ORGANIZATION/S, & TRAININGS/ SEMINARS ATTENDED',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
            'name' => 'II. ACCOMPLISHMENTS BASED ON OPCR',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => '',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => '',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'III. ATTENDANCE IN UNIVERSITY FUNCTION',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'IV. REQUESTS AND QUERIES ACTED UPON',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 17,
            'footers' => json_encode([
                '* Simple, Complex, Highly Technical'
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::create([
            'name' => 'V. SPECIAL TASKS',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => null,
            'footers' => json_encode([
                '* International, National, Local',
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::create([
            'name' => 'VI. OTHER ACCOMPLISHMENTS (IF ANY)',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'A. Research & Book Chapter (Production, Citation, Presentation)',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
            'name' => 'B. Inventions, Innovation, and Creative Works',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
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
        GenerateTable::create([
            'name' => 'C. Extension Program and Expert Service Rendered',
            'is_table' => 0,
            'type_id' => 1,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null,
        ]);
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
            'name' => 'D. Viable Demonstration Projects',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 20,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'E. Awards/ Recognitions Received by Office from  Reputable Organization',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 21,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'F. Community Relation and Outreach Program',
            'is_table' => 1,
            'type_id' => 1,
            'is_individual' => 0,
            'report_category_id' => 22,
            'footers' => null
        ]);

        //Academic
        GenerateTable::create([
            'name' => 'I. ACCOMPLISHMENT REPORT',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'A. ONGOING ADVANCED/ PROFESSIONAL STUDY',
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
        GenerateTable::create([
            'name' => 'B. OUTSTANDING ACHIEVEMENTS/ AWARDS, OFFICERSHIP/MEMBERSHIP IN PROFESSIONAL ORGANIZATION/S, & TRAININGS/ SEMINARS ATTENDED',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
            'name' => 'C. Research & Book Chapter (Production, Citation, Presentation)',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
            'name' => 'D. Faculty Inventions, Innovation, and Creative Works',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
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
        GenerateTable::create([
            'name' => 'E. Extension Program and Expert Service Rendered',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
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
        GenerateTable::create([
            'name' => 'F. Academic Program Development',
            'is_table' => 0,
            'type_id' => 2,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'F.1. Instructional Material, Reference/Text Book, Module, Monographs',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 15,
            'footers' => json_encode([
               '* International,National, Regional, Provincial/City/Municipal, Local-PUP' 
            ], JSON_FORCE_OBJECT)
        ]);
        GenerateTable::create([
            'name' => 'F.2. Course Syllabus/ Guide Developed/Revised/Enhanced',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => 16,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'III. SPECIAL TASKS',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => '',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => '',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'IV. ATTENDANCE IN UNIVERSITY FUNCTION',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 1,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'V. Viable Demonstration Projects',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 0,
            'report_category_id' => 20,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'VI.  Awards/ Recognitions Received by College/Branch/Campus from Reputable Organizations',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 0,
            'report_category_id' => 21,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'VII. Students Awards/ Recognitions from Reputable Organizations',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 0,
            'report_category_id' => 18,
        ]);
        GenerateTable::create([
            'name' => 'VIII. Community Relation and Outreach Program',
            'is_table' => 1,
            'type_id' => 2,
            'is_individual' => 0,
            'report_category_id' => 22,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'IX. STUDENTS TRAININGS AND SEMINARS',
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
        GenerateTable::create([
            'name' => 'DEPARTMENT LEVEL ACCOMPLISHMENTS (TO BE FILLED-IN BY CHAIRPERSON)																	            ',
            'is_table' => 0,
            'type_id' => 3,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'A. Technical Extension Programs/Projects/Activities',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 23,
        ]);
        GenerateTable::create([
            'name' => 'B. Community Relations and Outreach Programs',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 22,
        ]);
        GenerateTable::create([
            'name' => 'C. Viable Demonstration Projects',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 20,
        ]);
        GenerateTable::create([
            'name' => 'D. Awards/Recognitions Received by the Department from Reputable Organizations',
            'is_table' => 1,
            'type_id' => 3,
            'is_individual' => 0,
            'report_category_id' => 21,
        ]);
        /* College Level Tables */
        GenerateTable::create([
            'name' => 'COLLEGE LEVEL ACCOMPLISHMENTS (TO BE FILLED-IN BY DEAN)																	            ',
            'is_table' => 0,
            'type_id' => 4,
            'is_individual' => null,
            'report_category_id' => null,
            'footers' => null
        ]);
        GenerateTable::create([
            'name' => 'A. Technical Extension Programs/Projects/Activities',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 23,
        ]);
        GenerateTable::create([
            'name' => 'B. Community Relations and Outreach Programs',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 22,
        ]);
        GenerateTable::create([
            'name' => 'C. Viable Demonstration Projects',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 20,
        ]);
        GenerateTable::create([
            'name' => 'D. Awards/Recognitions Received by the College/Branch/Campus/Office from Reputable Organizations',
            'is_table' => 1,
            'type_id' => 4,
            'is_individual' => 0,
            'report_category_id' => 21,
        ]);
    }
}
