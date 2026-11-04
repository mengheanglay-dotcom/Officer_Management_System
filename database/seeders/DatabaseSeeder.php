<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Officer;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin', 'password' => '12345678']
        );
        $admin->forceFill(['role' => 'admin', 'approval_status' => 'approved', 'is_active' => true])->save();

        $depts = collect([
            ['Human Resources', 'Building A', 'Floor 1'],
            ['Finance', 'Building A', 'Floor 2'],
            ['Information Technology', 'Building B', 'Floor 3'],
            ['Public Relations', 'Building C', 'Floor 1'],
        ])->map(function (array $d): Department {
            return Department::updateOrCreate(
                ['dept_name' => $d[0]],
                [
                    'building_id' => Building::firstOrCreate(['name' => $d[1]])->id,
                    'floor_id' => Floor::firstOrCreate(['name' => $d[2]])->id,
                ]
            );
        });

        $officers = [
            ['Sok Dara',       'sokdara@gmail.com',   'Male',   '1990-03-12', 'Chief Officer',         '2018-01-15', 0],
            ['Chan Sreyneang', 'sreyneang@gmail.com', 'Female', '1992-07-21', 'HR Officer',            '2020-05-04', 0],
            ['Kim Vanna',      'kimvanna@gmail.com',  'Male',   '1988-11-02', 'Finance Manager',       '2017-09-01', 1],
            ['Heng Sotheary',  'sotheary@gmail.com',  'Female', '1995-01-30', 'Accountant',            '2022-02-14', 1],
            ['Lim Rithy',      'limrithy@gmail.com',  'Male',   '1991-06-18', 'System Administrator',  '2019-08-12', 2],
            ['Nget Chantha',   'chantha@gmail.com',   'Female', '1996-09-09', 'Software Officer',      '2023-03-20', 2],
            ['Pich Vireak',    'vireak@gmail.com',    'Male',   '1989-12-25', 'Media Officer',         '2016-11-07', 3],
            ['Roth Malis',     'malis@gmail.com',     'Female', '1994-04-05', 'Communication Officer', '2021-06-28', 3],
        ];

        foreach ($officers as [$name, $email, $gender, $dob, $position, $hired, $d]) {
            Officer::firstOrCreate(['email' => $email], [
                'officer_name' => $name,
                'gender' => $gender,
                'dob' => $dob,
                'position' => $position,
                'hire_date' => $hired,
                'dept_id' => $depts[$d]->dept_id,
            ]);
        }

        $hods = [
            'Human Resources' => 'sokdara@gmail.com',
            'Finance' => 'kimvanna@gmail.com',
            'Information Technology' => 'limrithy@gmail.com',
            'Public Relations' => 'vireak@gmail.com',
        ];

        foreach ($depts as $dept) {
            $dept->update(['hod_id' => Officer::where('email', $hods[$dept->dept_name])->value('officer_id')]);
        }
    }
}
