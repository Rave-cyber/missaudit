@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Add New Employee</h1>

    <form action="{{ route('admin.employee.store') }}" method="POST" class="max-w-lg">
        @csrf
        <div class="mb-4">
            <label for="first_name" class="block mb-2">First Name</label>
            <input type="text" name="first_name" id="first_name" 
                   class="w-full px-3 py-2 border rounded" 
                   value="{{ old('first_name') }}" required>
            @error('first_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="last_name" class="block mb-2">Last Name</label>
            <input type="text" name="last_name" id="last_name" 
                   class="w-full px-3 py-2 border rounded" 
                   value="{{ old('last_name') }}" required>
            @error('last_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block mb-2">Email</label>
            <input type="email" name="email" id="email" 
                   class="w-full px-3 py-2 border rounded" 
                   value="{{ old('email') }}" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="phone" class="block mb-2">Phone (Optional)</label>
            <input type="text" name="phone" id="phone" 
                   class="w-full px-3 py-2 border rounded" 
                   value="{{ old('phone') }}">
        </div>

        <div class="mb-4">
            <label for="position" class="block mb-2">Position (Optional)</label>
            <input type="text" name="position" id="position" 
                   class="w-full px-3 py-2 border rounded" 
                   value="{{ old('position') }}">
        </div>

        <div class="mb-4">
            <label for="hire_date" class="block mb-2">Hire Date (Optional)</label>
            <input type="date" name="hire_date" id="hire_date" 
                   class="w-full px-3 py-2 border rounded" 
                   value="{{ old('hire_date') }}">
        </div>

        <!-- <div class="mb-4">
            <label for="status" class="block mb-2">Status</label>
            <select name="status" id="status" class="w-full px-3 py-2 border rounded" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div> -->

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Create Employee
        </button>
    </form>
</div>
@endsection