<?php

namespace Database\Seeders;

use App\Models\Dean;
use App\Models\User;
use App\Models\SectorHead;
use App\Models\Chairperson;
use Illuminate\Database\Seeder;
use App\Models\FacultyResearcher;
use App\Models\FacultyExtensionist;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        SectorHead::truncate();
        
        //Super Admin
        // 1
//         User::create([
//             'email' => 'superadmin1@mailinator.com',
//             'password'=> Hash::make('superadmin1@mailinator.com'),
//             'date_of_birth' => 1992-04-24,
//             'first_name' => 'Super Admin',
//             'middle_name' => null,
//             'last_name' => 'Super Admin',
//             'suffix' => null,
//         ]);
//         // 2
//         User::create([
//             'email' => 'superadmin2@mailinator.com',
//             'password'=> Hash::make('superadmin2@mailinator.com'),
//             'date_of_birth' => 1992-04-24,
//             'first_name' => 'Super Admin',
//             'middle_name' => null,
//             'last_name' => 'Super Admin',
//             'suffix' => null,
//         ]);

//         //Faculty
//         // 3
//         User::create([
//             'email' => 'faculty_taguig@mailinator.com',
//             'password'=> Hash::make('faculty_taguig@mailinator.com'),
//             'date_of_birth' => 1992-04-24,
//             'first_name' => 'Faculty-Taguig',
//             'middle_name' => null,
//             'last_name' => 'Faculty-Taguig',
//             'suffix' => null,
//         ]);

//         // 4
//         User::create([
//             'email' => 'faculty_caf_acc@mailinator.com',
//             'password'=> Hash::make('faculty_caf_acc@mailinator.com'),
//             'date_of_birth' => 1992-04-24,
//             'first_name' => 'Faculty-Accountancy',
//             'middle_name' => null,
//             'last_name' => 'Faculty-Acountancy',
//             'suffix' => null,
//         ]);
//         // 5-15
//         for ($f = 1; $f <= 11; $f++) {
//             User::create([
//                 'email' => 'faculty'.$f.'@mailinator.com',
//                 'password'=> Hash::make('faculty'.$f.'@mailinator.com'),
//                 'date_of_birth' => 1992-04-24,
//                 'first_name' => 'Faculty',
//                 'middle_name' => null,
//                 'last_name' => 'Faculty',
//                 'suffix' => null,
//             ]);
//         }

//         //16
//         //Faculty with designation
//         User::create([
//             'email' => 'loey@mailinator.com',
//             'password'=> Hash::make('loey@mailinator.com'),
//             'date_of_birth' => 2000-04-24,
//             'first_name' => 'Faculty Designate',
//             'middle_name' => null,
//             'last_name' => 'Faculty Designate',
//             'suffix' => null,
//         ]);
//         //17
//         User::create([
//             'email' => 'miro@mailinator.com',
//             'password'=> Hash::make('miro@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Faculty Designate',
//             'middle_name' => null,
//             'last_name' => 'Faculty Designate',
//             'suffix' => null,
//         ]);
//         // 18-28
//         for ($i = 1; $i <= 11; $i++) {
//             User::create([
//                 'email' => 'facultydesignate'.$i.'@mailinator.com',
//                 'password'=> Hash::make('facultydesignate'.$i.'@mailinator.com'),
//                 'date_of_birth' => 1992-04-24,
//                 'first_name' => 'Faculty Designate',
//                 'middle_name' => null,
//                 'last_name' => 'Faculty Designate',
//                 'suffix' => null,
//             ]);
//         }

//         //Admin Employee 29- 41
//         User::create([
//             'email' => 'harry@mailinator.com',
//             'password'=> Hash::make('harry@mailinator.com'),
//             'date_of_birth' => 2000-04-24,
//             'first_name' => 'Admin Employee',
//             'middle_name' => null,
//             'last_name' => 'Admin Employee',
//             'suffix' => null,
//         ]);

//         User::create([
//             'email' => 'mira@mailinator.com',
//             'password'=> Hash::make('mira@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Admin Employee',
//             'middle_name' => null,
//             'last_name' => 'Admin Employee',
//             'suffix' => null,
//         ]);

//         for ($a = 1; $a <= 11; $a++) {
//             User::create([
//                 'email' => 'admin'.$a.'@mailinator.com',
//                 'password'=> Hash::make('admin'.$a.'@mailinator.com'),
//                 'date_of_birth' => 1992-04-24,
//                 'first_name' => 'Admin Employee',
//                 'middle_name' => null,
//                 'last_name' => 'Admin Employee',
//                 'suffix' => null,
//             ]);
//         }
// //42
//         //Admin with Teaching Load
//         User::create([
//             'email' => 'gola@mailinator.com',
//             'password'=> Hash::make('gola@mailinator.com'),
//             'date_of_birth' => 2000-04-24,
//             'first_name' => 'Admin Teaching',
//             'middle_name' => null,
//             'last_name' => 'Admin Teaching',
//             'suffix' => null,
//         ]);

//         User::create([
//             'email' => 'sorita@mailinator.com',
//             'password'=> Hash::make('sorita@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Admin Teaching',
//             'middle_name' => null,
//             'last_name' => 'Admin Teaching',
//             'suffix' => null,
//         ]);

//         for ($at = 1; $at <= 11; $at++) {
//             User::create([
//                 'email' => 'adminteaching'.$at.'@mailinator.com',
//                 'password'=> Hash::make('adminteaching'.$at.'@mailinator.com'),
//                 'date_of_birth' => 1992-04-24,
//                 'first_name' => 'Admin Teaching',
//                 'middle_name' => null,
//                 'last_name' => 'Admin Teaching',
//                 'suffix' => null,
//             ]);
//         }
// //54
//         Chairperson::truncate();

//         //Chairperson
//         $cp1 = User::create([
//             'email' => 'chairperson_caf_accountancy@mailinator.com',
//             'password'=> Hash::make('chairperson_caf_accountancy@mailinator.com'),
//             'date_of_birth' => 2000-04-24,
//             'first_name' => 'Chairperson ',
//             'middle_name' => null,
//             'last_name' => 'CAF-DA',
//             'suffix' => null,
//         ]);

//         Chairperson::create([
//             'user_id' => $cp1->id,
//             'department_id' => 60,
//             'college_id' => 5,
//         ]);


//         $cp2 = User::create([
//             'email' => 'chairperson_taguig@mailinator.com',
//             'password'=> Hash::make('chairperson_taguig@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Chairperson',
//             'middle_name' => null,
//             'last_name' => 'Taguig',
//             'suffix' => null,
//         ]);

//         Chairperson::create([
//             'user_id' => $cp2->id,
//             'department_id' => 292,
//             'college_id' => 86,
//         ]);
// //56
//         Dean::truncate();
//         //Director/Dean
//         $dean1 = User::create([
//             'email' => 'dean_caf@mailPinator.com',
//             'password'=> Hash::make('dean_caf@mailinator.com'),
//             'date_of_birth' => 2000-04-24,
//             'first_name' => 'Dean',
//             'middle_name' => null,
//             'last_name' => 'CAF',
//             'suffix' => null,
//         ]);

//         Dean::create([
//             'user_id' => $dean1->id,
//             'college_id' => 5,
//         ]);

//         $dean2 = User::create([
//             'email' => 'dean_taguig@mailinator.com',
//             'password'=> Hash::make('dean_taguig@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Dean',
//             'middle_name' => null,
//             'last_name' => 'Taguig',
//             'suffix' => null,
//         ]);

//         Dean::create([
//             'user_id' => $dean2->id,
//             'college_id' => 86,
//         ]);

// //58
//         //VP/Sector head
//         $head1 = User::create([
//             'email' => 'sectorhead1@mailinator.com',
//             'password'=> Hash::make('sectorhead1@mailinator.com'),
//             'date_of_birth' => 2000-04-24,
//             'first_name' => 'Sector Head',
//             'middle_name' => null,
//             'last_name' => 'OVPAA',
//             'suffix' => null,
//         ]);


//         SectorHead::create([
//             'user_id' => $head1->id,
//             'sector_id' => 3,
//         ]);

//         $head2 = User::create([
//             'email' => 'sectorhead2@mailinator.com',
//             'password'=> Hash::make('sectorhead2@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Sector Head',
//             'middle_name' => null,
//             'last_name' => 'Sector Head',
//             'suffix' => null,
//         ]);

//         SectorHead::create([
//             'user_id' => $head2->id,
//             'sector_id' => 8,
//         ]);
// //60
//         //IPQMSO
//         User::create([
//             'email' => 'ipqmso1@mailinator.com',
//             'password'=> Hash::make('ipqmso1@mailinator.com'),
//             'date_of_birth' => 2000-04-24,
//             'first_name' => 'IPQMSO',
//             'middle_name' => null,
//             'last_name' => 'IPQMSO',
//             'suffix' => null,
//         ]);

//         User::create([
//             'email' => 'ipqmso2@mailinator.com',
//             'password'=> Hash::make('ipqmso2@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'IPQMSO',
//             'middle_name' => null,
//             'last_name' => 'IPQMSO',
//             'suffix' => null,
//         ]);
// //62
//         //Faculty Extensionist
//         $ext1 = User::create([
//             'email' => 'facultyext1@mailinator.com',
//             'password'=> Hash::make('facultyext1@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Extensionist',
//             'middle_name' => null,
//             'last_name' => 'CAF-DA',
//             'suffix' => null,
//         ]);

//         FacultyExtensionist::truncate();
//         FacultyExtensionist::create([
//             'user_id' => $ext1->id,
//             'department_id' => 60,
//         ]);

//         $ext2 = User::create([
//             'email' => 'facultyext2@mailinator.com',
//             'password'=> Hash::make('facultyext2@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Extensionist',
//             'middle_name' => null,
//             'last_name' => 'TG',
//             'suffix' => null,
//         ]);

// //64
//         FacultyExtensionist::create([
//             'user_id' => $ext2->id,
//             'department_id' => 292,
//         ]);

//         //FAculty researchers
//         $res1 = User::create([
//             'email' => 'facultyres1@mailinator.com',
//             'password'=> Hash::make('facultyres1@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Researcher',
//             'middle_name' => null,
//             'last_name' => 'CAF-DA',
//             'suffix' => null,
//         ]);

//         FacultyResearcher::truncate();
//         FacultyResearcher::create([
//             'user_id' => $res1->id,
//             'department_id' => 60,
//         ]);

//         $res2 = User::create([
//             'email' => 'facultyres2@mailinator.com',
//             'password'=> Hash::make('facultyres2@mailinator.com'),
//             'date_of_birth' => 1998-04-24,
//             'first_name' => 'Researcher',
//             'middle_name' => null,
//             'last_name' => 'TG',
//             'suffix' => null,
//         ]);

//         FacultyResearcher::create([
//             'user_id' => $res2->id,
//             'department_id' => 292,
//         ]);
    }
}
