@props(['disabled' => false, 'name' => null])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['name' => $name, 'class' => classNames('rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50', ['border-red-800' => $errors->has($name), 'opacity-50' => $disabled])]) !!}>
