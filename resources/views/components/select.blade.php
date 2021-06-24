@props(['disabled' => false, 'name' => null, 'value' => null, 'options' => []])

{{ html()->select($name, $options, $value)->disabled($disabled)->class([$attributes->get('class'), 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50', 'border-red-800' => $errors->has($name)]) }}
