@props(['disabled' => false, 'name' => null])

<input {{ $disabled ? 'disabled' : '' }} name="{{ $name }}" {!! $attributes->merge(['class' => classNames('dark:text-gray-400 dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:border-indigo-300 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-500 focus:ring-opacity-50 rounded-md shadow-sm transition', ['border-red-800' => $errors->has($name), 'opacity-50' => $disabled])]) !!}>
