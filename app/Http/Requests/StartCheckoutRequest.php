<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Valida el plan con el que arranca el checkout.
 *
 * El controlador anterior tomaba el segmento de la URL y lo pegaba tal cual
 * en el enlace a Mercado Pago, así que cualquiera podía forzar un
 * `preapproval_plan_id` arbitrario — el de otro comercio, por ejemplo — y
 * dejar al usuario suscripto a algo que la app no reconoce. Acá el valor
 * tiene que ser uno de los planes configurados o ser alguna de sus claves.
 */
class StartCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * El plan viaja como parámetro de ruta, no como campo del formulario.
     *
     * @return array<string, mixed>
     */
    public function validationData(): array
    {
        return ['variant' => $this->route('variant')];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'variant' => ['required', 'string', Rule::in($this->allowedVariants())],
        ];
    }

    /**
     * Identificador de plan de Mercado Pago ya resuelto.
     */
    public function planId(): string
    {
        $variant = (string) $this->validated('variant');

        return $this->configuredPlans()[$variant] ?? $variant;
    }

    /**
     * Se aceptan tanto las claves legibles ("monthly") como los identificadores
     * crudos, porque los enlaces de la landing usan las primeras y los que
     * vuelven de Mercado Pago usan los segundos.
     *
     * @return list<string>
     */
    private function allowedVariants(): array
    {
        $plans = $this->configuredPlans();

        return array_values(array_unique([...array_keys($plans), ...array_values($plans)]));
    }

    /**
     * @return array<string, string>
     */
    private function configuredPlans(): array
    {
        return array_filter(config('services.mercadopago.plans', []));
    }
}
