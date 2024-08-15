<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

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
        return parent::mutateFormDataBeforeCreate($data); // TODO: Change the autogenerated stub
    }
}
