<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use App\Models\Employee;
use App\Models\Leave;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    /**
     * @param array $data
     * @return array
     * @throws Halt
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $today_attendance = (new Leave())->query()->where('employee_id', $data['employee_id'])->where('status', '=', 'active')->first();
        if ($today_attendance) {
            Notification::make()->warning()
                ->title('Employee already on leave.')
                ->body(''.(Employee::where('id',$data['employee_id'])->first()->user->name).' already on leave.')->broadcast(Auth::user())->send();
            $this->halt();
        }
        return parent::mutateFormDataBeforeCreate($data);
    }
}
