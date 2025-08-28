<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportContactsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'xml_file' => 'required|file|max:2048',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('xml_file')) {
                $file = $this->file('xml_file');
                $content = file_get_contents($file->getPathname());
                
                // Check if it's valid XML content
                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($content);
                $errors = libxml_get_errors();
                
                if ($xml === false || !empty($errors)) {
                    $validator->errors()->add('xml_file', 'The file must contain valid XML content.');
                }
                
                libxml_clear_errors();
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'xml_file.required' => 'Please select an XML file to import.',
            'xml_file.file' => 'The uploaded file is invalid.',
            'xml_file.max' => 'The file size may not be greater than 2MB.',
        ];
    }
}
