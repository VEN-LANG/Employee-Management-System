<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Employee;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Attendance Created';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Attendance Created')
            ->body('The attendance has been created successfully.');
    }

    /**
     * @param array $data
     * @return array
     * @throws Halt
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Validate that check_in is not greater than check_out
        if (isset($data['check_in']) && isset($data['check_out']) && $data['check_in'] > $data['check_out']) {
            Notification::make()->warning()
                ->title('Time Error!')
                ->body('The check-out time must be after the check-in time.')
                ->send();
            $this->halt();
        }

        $today_attendance = (new Attendance())->query()->where('employee_id', $data['employee_id'])->whereBetween('date', [now()->startOfDay(), now()->endOfDay()])->first();
        if ($today_attendance) {
            Notification::make()->warning()
                ->title('Employee already checked in today')
                ->body(''.(Employee::where('id',$data['employee_id'])->first()->user->name).' already checked in today.')->broadcast(Auth::user())->send();
            $this->halt();
        }
        return parent::mutateFormDataBeforeCreate($data);
    }
}
