<?php

namespace App\Http\Resources;

use Dedoc\Scramble\Attributes\SchemaName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
#[SchemaName('User')]
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * ID
             * @example 1
             */
            'id' => $this->id,
            /**
             * 使用者名稱
             * @example "John Doe"
             */
            'name' => $this->name,
            /**
             * 使用者 Email
             * @example "test@example.com"
             */
            'email' => $this->email,
            /** @example "2026-01-01 00:00:00" */
            'created_at' => $this->created_at,
            /** @example "2026-01-01 00:00:00" */
            'updated_at' => $this->updated_at,
        ];
    }
}
