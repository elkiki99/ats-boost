<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function view(User $user, Document $document): bool
    {
        return $user->id === $document->user_id;
    }

    /**
     * Descargar exige suscripción vigente además de propiedad: el documento
     * ya generado no puede seguir siendo accesible después de que la
     * suscripción venció.
     */
    public function download(User $user, Document $document): bool
    {
        return $this->view($user, $document) && $user->isSubscribed();
    }

    public function update(User $user, Document $document): bool
    {
        return $this->download($user, $document);
    }

    public function delete(User $user, Document $document): bool
    {
        return $this->view($user, $document);
    }
}
