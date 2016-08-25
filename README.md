Laravel VO
==========

_Making the most of value objects in Laravel_

A simple ValueObject abstract class and ValueObjectCollection class that will help you make the most
of value objects in Laravel.

## Installation

Install through composer:

```
composer require antennaio/laravel-vo:~0.0.1
```

## Usage - ValueObject

Here is a simple example of a Domain value object:

```
use Antennaio\VO\ValueObject;
use InvalidArgumentException;

class Domain extends ValueObject
{
    protected function validate($value)
    {
        if (!preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/', $value)) {
            throw new InvalidArgumentException('Domain is invalid: '.$value);
        }
    }
}
```

```
class MyModel extends Model
{
    ...

    public function getDomainsAttribute($domain)
    {
        return new Domain($domain);
    }

    public function setDomainAttribute(Domain $domain)
    {
        $this->attributes['domain'] = $domain;
    }
}
```

Now whenever the `domain` attribute is set you are expected to pass a Domain object:

```
$myModel = new MyModel;
$myModel->domain = new Domain('google.com');
```

In a truly DRY fashion a value object can be used to perform validation in your requests:

```
Validator::extend('domain', function ($attribute, $value, $parameters, $validator) {
    try {
        new Domain($value);
    } catch (InvalidArgumentException $e) {
        return false;
    }

    return true;
});
```

Using the custom validator to validate the `domain` field:

```
use App\Http\Requests\Request;

class SampleRequest extends Request
{
    ...

    public function rules()
    {
        return ['domain' => 'required|domain'];
    }
}
```

## Usage - ValueObjectCollection

Sometimes it's useful to collect and store multiple value objects together. This is when ValueObjectCollection
comes into play. Creating an immutable ValueObjectCollection is as simple as telling it which value objects will be
stored as part of the collection.

```
class DomainCollection extends ValueObjectCollection
{
    protected $valueObject = Domain::class;
}
```

```
class MyModel extends Model
{
    ...

    public function getDomainsAttribute($domains)
    {
        return new DomainCollection(unserialize($domains));
    }

    public function setDomainsAttribute(DomainCollection $domains)
    {
        $this->attributes['domains'] = serialize($domains->toArray());
    }
}
```

In the example below DomainCollection will __ONLY__ accept valid domain names or throw an
`InvalidArgumentException`.

```
$myModel = new MyModel;
$myModel->domains = new DomainCollection(['google.com', 'amazon.com']);

// nah - InvalidArgumentException thrown
$myModel->domains = new DomainCollection(['google.com', 'amazon']);
```

Usually a collection is created out of user input, that's why you can also pass a string that will get parsed
when a new collection is created:

```
$myModel = new MyModel;
$myModel->domains = new DomainCollection('google.com, amazon.com');
```

The delimiter (a comma by default) can be adjusted by setting `delimiter` property in DomainCollection.

Dislaying the domain collection in the view:

```
// will output a comma separated list of domain names: google.com, amazon.com
{{ $myModel->domains }}
```

Finally, just like in case of ValueObject, ValueObjectCollection can be used to create a custom validator:

```
Validator::extend('domains', function ($attribute, $value, $parameters, $validator) {
    try {
        new DomainCollection($domains);
    } catch (InvalidArgumentException $e) {
        return false;
    }

    return true;
});
```

Validating a collection of domains:

```
use App\Http\Requests\Request;

class SampleRequest extends Request
{
    ...

    public function rules()
    {
        return ['domains' => 'required|domains'];
    }
}
```

## Other examples

For another example of HexColor and HexColorCollection check out the tests/* directory.
