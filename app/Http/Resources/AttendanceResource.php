<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'time_in' => $this->time_in,
            'day' => $this->day,
            'remarks' => $this->remarks,
            'student' => new StudentResource($this->whenLoaded('student')),
        ];
    }
}
