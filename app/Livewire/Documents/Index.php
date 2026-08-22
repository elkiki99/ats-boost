<?php

namespace App\Livewire\Documents;

use App\Enums\DocumentType;
use App\Models\Document;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Historial de documentos generados.
 */
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'tipo', except: '')]
    public string $type = '';

    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(Document $document): void
    {
        Gate::authorize('delete', $document);

        $document->delete();

        Flux::toast(
            heading: 'Documento eliminado',
            text: 'Ya no aparece en tu historial.',
            variant: 'success',
        );
    }

    /**
     * @return array<string, string>
     */
    public function typeOptions(): array
    {
        $options = ['' => 'Todos'];

        foreach (DocumentType::cases() as $type) {
            $options[$type->value] = $type->label();
        }

        return $options;
    }

    public function render(): View
    {
        $documents = auth()->user()
            ->documents()
            ->ofType($this->type ?: null)
            ->when($this->search !== '', fn ($query) => $query->where(
                fn ($q) => $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('company', 'like', "%{$this->search}%")
                    ->orWhere('role', 'like', "%{$this->search}%")
            ))
            ->latestFirst()
            ->paginate(12);

        return view('livewire.documents.index', compact('documents'))
            ->title('Mis documentos • ATS Boost');
    }
}
