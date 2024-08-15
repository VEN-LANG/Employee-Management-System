<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Attendance Updated!';
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Attendance Updated')
            ->body('The attendance has been updated successfully.');
    }

    /**
     * @param array $data
     * @return array
     * @throws Halt
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Validate that check_in is not greater than check_out
        if (isset($data['check_in']) && isset($data['check_out']) && $data['check_in'] > $data['check_out']) {
            Notification::make()->warning()
                ->title('Time Error!')
                ->body('The check-out time must be after the check-in time.')
                ->send();
            $this->halt();
        }

        return $data;
    }
}
