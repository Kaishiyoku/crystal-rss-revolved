@props(['disabled' => false, 'name' => null, 'value' => null, 'options' => []])

{{ html()->select($name, $options, $value)->disabled($disabled)->class([$attributes->get('class'), 'dark:bg-gray-800 rounded-md shadow-sm border-gray-300 dark:border-gray-600 focus:border-primary-300 dark:focus:border-primary-500 focus:ring focus:ring-primary-200 dark:focus:ring-primary-500 focus:ring-opacity-50', 'border-red-800' => $errors->has($name)]) }}
