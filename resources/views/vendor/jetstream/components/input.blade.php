@props(['disabled' => false, 'name' => null])

<input {{ $disabled ? 'disabled' : '' }} name="{{ $name }}" {!! $attributes->merge(['class' => classNames('dark:text-gray-500/75 dark:bg-gray-900/50 border-gray-500/30 dark:border-gray-700/50 focus:border-primary-300 dark:focus:border-primary-500 focus:ring focus:ring-primary-200 dark:focus:ring-primary-500 focus:ring-opacity-50 rounded-md shadow-sm transition', ['border-pink-800' => $errors->has($name), 'opacity-50' => $disabled])]) !!}>
