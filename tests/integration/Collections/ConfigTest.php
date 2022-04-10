<?php

use Itoufo\Tests\Models\User;

class ConfigTest extends NotiferTestCase
{
    public function testIsPolymorphic()
    {
        $config = app('notifer.config');
        $this->assertInternalType('bool', $config->isPolymorphic());
    }

    public function testIsStrict()
    {
        $config = app('notifer.config');
        $this->assertInternalType('bool', $config->isStrict());
    }

    public function testIsTranslated()
    {
        $config = app('notifer.config');
        $this->assertInternalType('bool', $config->isTranslated());
    }

    public function testGetNotifiedModel()
    {
        $config = app('notifer.config');
        $this->assertInternalType('string', $config->getNotifiedModel());
        $this->assertSame(User::class, $config->getNotifiedModel());
    }

    public function testGetNotifiedModelFail()
    {
        $this->expectException(InvalidArgumentException::class);

        $config = app('notifer.config');
        $config->set('model', 'undefined_class_name');
        $config->getNotifiedModel();
    }

    public function testGetAdditionalFields()
    {
        $config = app('notifer.config');
        $config->set('additional_fields.fillable', ['fillable_field']);
        $config->set('additional_fields.required', ['required_field']);
        $this->assertInternalType('array', $config->getAdditionalFields());
        $this->assertCount(2, $config->getAdditionalFields());
        $this->assertSame(['required_field', 'fillable_field'], $config->getAdditionalFields());
    }

    public function testGetAdditionalFieldsFillable()
    {
        $config = app('notifer.config');
        $config->set('additional_fields.fillable', ['fillable_field']);
        $this->assertInternalType('array', $config->getAdditionalFields());
        $this->assertSame(['fillable_field'], $config->getAdditionalFields());
    }

    public function testGetAdditionalFieldsRequired()
    {
        $config = app('notifer.config');
        $config->set('additional_fields.required', ['required_field']);
        $this->assertInternalType('array', $config->getAdditionalFields());
        $this->assertSame(['required_field'], $config->getAdditionalFields());
    }

    public function testGetAdditionalFieldsEmpty()
    {
        $config = app('notifer.config');
        $this->assertInternalType('array', $config->getAdditionalFields());
        $this->assertSame([], $config->getAdditionalFields());
    }

    public function testGetAdditionalRequiredFields()
    {
        $config = app('notifer.config');
        $this->assertInternalType('array', $config->getAdditionalRequiredFields());
        $this->assertSame([], $config->getAdditionalRequiredFields());
    }

    public function testGetTranslationDomain()
    {
        $config = app('notifer.config');
        $this->assertInternalType('string', $config->getTranslationDomain());
        $this->assertSame('notifer', $config->getTranslationDomain());
    }

    public function testHasTrue()
    {
        $config = app('notifer.config');
        $this->assertTrue($config->has('polymorphic'));
    }

    public function testHasFalse()
    {
        $config = app('notifer.config');
        $this->assertFalse($config->has('undefined_config_key'));
    }

    public function testSet()
    {
        $config = app('notifer.config');
        $config->set('polymorphic', true);
        $this->assertTrue($config->get('polymorphic'));
    }

    public function testGetOverloaded()
    {
        $config = app('notifer.config');
        $this->assertInternalType('bool', $config->polymorphic);
    }

    public function testSetOverloaded()
    {
        $config = app('notifer.config');

        $config->polymorphic = true;
        $this->assertInternalType('bool', $config->polymorphic);
        $this->assertTrue($config->get('polymorphic'));
    }
}
