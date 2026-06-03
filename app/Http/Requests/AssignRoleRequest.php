<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only Super Admin and Kemahasiswaan can assign roles
        $user = $this->user();
        return $user && ($user->isSuperAdmin() || $user->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $requester = $this->user();
        $allowedRoles = [];

        // Super Admin can assign any role
        if ($requester->isSuperAdmin()) {
            $allowedRoles = ['Super Admin', 'Kemahasiswaan', 'DPMBEM', 'Ormawa', 'Ormawa Institusi', 'Ormawa Prodi', 'Mahasiswa'];
        }
        // Kemahasiswaan can only assign Ormawa, Mahasiswa, DPMBEM (not Super Admin)
        elseif ($requester->isAdmin()) {
            $allowedRoles = ['Ormawa', 'Ormawa Institusi', 'Ormawa Prodi', 'Mahasiswa', 'DPMBEM'];
        }

        return [
            'role' => [
                'required',
                'string',
                'in:' . implode(',', $allowedRoles),
            ],
            'ormawa_type' => [
                'nullable',
                'string',
                'in:institusi,prodi',
                'required_if:role,Ormawa',
            ],
            'ormawa_name' => [
                'nullable',
                'string',
                'max:100',
                'required_if:role,Ormawa,Ormawa Institusi,Ormawa Prodi',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'role.required' => 'Role harus diisi',
            'role.in' => 'Role tidak valid atau Anda tidak memiliki izin untuk assign role tersebut',
            'ormawa_type.required_if' => 'Tipe Ormawa harus diisi ketika role adalah Ormawa',
            'ormawa_type.in' => 'Tipe Ormawa harus institusi atau prodi',
            'ormawa_name.required_if' => 'Nama Ormawa harus diisi ketika role adalah Ormawa',
            'ormawa_name.max' => 'Nama Ormawa tidak boleh lebih dari 100 karakter',
        ];
    }
}
