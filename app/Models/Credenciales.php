<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\CredencialesEncryptionService;

class Credenciales extends Model
{
    protected $table = 'credenciales';
    protected $primaryKey = 'clave';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'clave',
        'des_dns',
        'des_usuario',
        'des_db',
        'des_password',
    ];

    // Campos que deben ser cifrados (ahora incluye clave)
    protected $encryptedFields = ['clave', 'des_dns', 'des_usuario', 'des_db', 'des_password'];

    private $encryptionService;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->encryptionService = new CredencialesEncryptionService();
    }

    /**
     * Cifra los campos sensibles antes de guardar
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptedFields) && !empty($value)) {
            $value = $this->encryptionService->encrypt($value);
        }
        return parent::setAttribute($key, $value);
    }

    /**
     * Descifra los campos sensibles al acceder
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        if (in_array($key, $this->encryptedFields) && !empty($value)) {
            try {
                return $this->encryptionService->decrypt($value);
            } catch (\Exception $e) {
                // Si falla el descifrado, devolver el valor original
                return $value;
            }
        }
        
        return $value;
    }

    /**
     * Obtiene los datos sin cifrar para mostrar
     */
    public function getDecryptedAttributes()
    {
        $attributes = $this->getAttributes();
        
        foreach ($this->encryptedFields as $field) {
            if (isset($attributes[$field]) && !empty($attributes[$field])) {
                try {
                    $attributes[$field] = $this->encryptionService->decrypt($attributes[$field]);
                } catch (\Exception $e) {
                    // Mantener valor original si falla el descifrado
                }
            }
        }
        
        return $attributes;
    }

    /**
     * Buscar por clave descifrada
     */
    public static function findByDecryptedKey($claveDescifrada)
    {
        $service = new CredencialesEncryptionService();
        $credenciales = self::all();
        
        foreach ($credenciales as $credencial) {
            if ($credencial->clave === $claveDescifrada) {
                return $credencial;
            }
        }
        
        return null;
    }
}
