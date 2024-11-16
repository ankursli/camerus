<?php

namespace App\Forms;

use Themosis\Field\Contracts\FieldFactoryInterface;
use Themosis\Forms\Contracts\FormFactoryInterface;
use Themosis\Forms\Contracts\Formidable;
use Themosis\Forms\Contracts\FormInterface;

class SalonFilterForm implements Formidable
{
    /**
     * Build your form.
     *
     * @param FormFactoryInterface  $factory
     * @param FieldFactoryInterface $fields
     *
     * @return FormInterface
     */
    public function build(FormFactoryInterface $factory, FieldFactoryInterface $fields): FormInterface
    {
        //
		return $factory->make()
			->add($fields->text('fullname'))
			->add($fields->email('email'))
			->add($fields->textarea('message'))
			->add($fields->submit('send', [
				'label' => 'Contact Us'
			]))
			->get();
    }
}
