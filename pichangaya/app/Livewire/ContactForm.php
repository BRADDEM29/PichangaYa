namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact; // Asegúrate de crear el modelo o usar DB::table

class ContactForm extends Component
{
    public $name, $email, $subject, $message;
    public $successMessage;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'required',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        // Guardar en la base de datos
        \DB::table('contacts')->insert([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->reset(['name', 'email', 'subject', 'message']);
        $this->successMessage = '¡Mensaje enviado! Te responderemos en breve.';
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}