@props(['disabled' => false, 'name' => null, 'value' => null, 'options' => []])

{{ html()->select($name, $options, $value)->disabled($disabled)->class([$attributes->get('class'), 'dark:bg-gray-900/50 rounded-md shadow-sm border-gray-500/30 dark:border-gray-700/50 focus:border-primary-300 dark:focus:border-primary-500 focus:ring focus:ring-primary-200 dark:focus:ring-primary-500 focus:ring-opacity-50', 'border-pink-800' => $errors->has($name)]) }}
