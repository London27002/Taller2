<?php

namespace Empleados\Controllers;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_TransportException;
use Exception;
use Empleados\Models\Empleado;
use TCPDF;

class EmpleadoController
{
    private $jsonFile;

    public function __construct($jsonFile)
    {
        $this->jsonFile = $jsonFile;
    }

    public function readJsonFile()
    {
        if (!file_exists($this->jsonFile)) {
            return [];
        }

        $contents = file_get_contents($this->jsonFile);
        if ($contents === false) {
            throw new \Exception('Error al leer el archivo JSON.');
        }

        $data = json_decode($contents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Error al decodificar JSON.');
        }

        return $data;
    }

    public function add(Empleado $empleado)
    {
        $empleados = $this->readJsonFile();
        $empleados[] = $empleado->toArray();

        $json_data = json_encode($empleados, JSON_PRETTY_PRINT);
        if ($json_data === false) {
            throw new \Exception('Error al codificar JSON.');
        }

        if (file_put_contents($this->jsonFile, $json_data) === false) {
            throw new \Exception('Error al escribir el archivo JSON.');
        }

        $this->notifyEmail($empleado);
        $this->generatePdf($empleado);
    }

    public function notifyEmail(Empleado $empleado)
{
    // Crear una instancia de SwiftMailer
    $transport = (new Swift_SmtpTransport('sandbox.smtp.mailtrap.io', 2525))
        ->setUsername('be2f2255973453') 
        ->setPassword('ff086b37283899');

    $mailer = new Swift_Mailer($transport);

    // Crear el mensaje
    $message = (new Swift_Message('Fuiste añadido a la agenda de London'))
        ->setFrom(['gutierrezsebas27002@gmail.com' => 'Mailer'])
        ->setTo([$empleado->getEmail() => $empleado->getName()])
        ->setBody("Felicitaciones {$empleado->getName()}, fuiste añadido a la lista.")
        ->addPart('Felicitaciones ' . $empleado->getName() . ', fuiste añadido a la lista.', 'text/plain'); // Parte en texto plano

    try {
        // Enviar el mensaje
        $result = $mailer->send($message);

        // Verificar si el mensaje fue enviado exitosamente
        if ($result) {
            echo 'Mensaje enviado exitosamente.';
        } else {
            echo 'Error al enviar el mensaje.';
        }
    } catch (Swift_TransportException $e) {
        // Captura errores específicos del transporte
        echo 'Error de transporte: ' . $e->getMessage();
    } catch (Exception $e) {
        // Captura otros errores generales
        echo 'Error en el envío del mensaje: ' . $e->getMessage();
    }
}
public function generatePdf(Empleado $empleado)
{
    try {
        // Crear una nueva instancia de TCPDF
        $pdf = new TCPDF();
        
        // Configurar documento
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        
        // Contenido del PDF
        $html = "<h1>Detalles del Empleado</h1>";
        $html .= "<p><strong>Nombre:</strong> " . htmlspecialchars($empleado->getName()) . "</p>";
        $html .= "<p><strong>Email:</strong> " . htmlspecialchars($empleado->getEmail()) . "</p>";
        $html .= "<p><strong>Teléfono:</strong> " . htmlspecialchars($empleado->getPhone()) . "</p>";
        $html .= "<p><strong>Peso:</strong> " . htmlspecialchars($empleado->getWeight()) . "</p>";
        $html .= "<p><strong>Altura:</strong> " . htmlspecialchars($empleado->getHeight()) . "</p>";
        $html .= "<p><strong>IMC:</strong> " . number_format($empleado->calculateIMC(), 4) . "</p>";
        $html .= "<p><strong>Fecha de Contratación:</strong> " . htmlspecialchars($empleado->getHireDate()->format('Y-m-d')) . "</p>";
        $html .= "<p><strong>Tiempo en la Empresa:</strong> " . htmlspecialchars($empleado->timeInCompany()) . "</p>";
    
        
        // Escribir el contenido
        $pdf->writeHTML($html);
        
        // Ruta absoluta para guardar el archivo PDF en public/pdfs
        $filePath = __DIR__ . '/../../public/pdfs/' . urlencode($empleado->getName()) . '.pdf';
        
        // Guardar el PDF en un archivo
        if (!$pdf->Output($filePath, 'F')) {
            throw new \Exception('Error al guardar el archivo PDF.');
        }
        
        echo 'PDF generado exitosamente.';
    } catch (\Exception $e) {
        echo 'Error en la generación del PDF: ' . $e->getMessage();
    }
}


}
?>
