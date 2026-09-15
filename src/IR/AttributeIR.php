<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\IR;

final readonly class AttributeIR
{
  public function __construct(
    public string $name,
    public ?string $value,
    public bool $dynamic,
    public bool $boolean,
  ) {}

  /** @return array{name:string,value:?string,dynamic:bool,boolean:bool} */
  public function toArray(): array
  {
    return [
      'name' => $this->name,
      'value' => $this->value,
      'dynamic' => $this->dynamic,
      'boolean' => $this->boolean,
    ];
  }
}
