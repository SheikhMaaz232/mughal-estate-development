<?php

return [
    'working_hours_per_day' => 8,

    'include_leave_in_payroll' => true,

    'include_holiday_in_payroll' => true,

    'payroll_statuses' => [
        'present' => ['present', 'late', 'half_day', 'manual'],
        'paid_leave' => ['leave'],
        'holiday' => ['holiday'],
    ],
];
