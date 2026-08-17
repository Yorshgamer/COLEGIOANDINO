<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WebController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Welcome');
    }

    public function presentacion(): Response
    {
        return Inertia::render('Web/Presentacion', [
            'title' => 'Presentación Institucional'
        ]);
    }

    public function iglesiaMetodista(): Response
    {
        return Inertia::render('Web/IglesiaMetodista', [
            'title' => 'Nuestra Historia - Iglesia Metodista'
        ]);
    }

    public function misionVision(): Response
    {
        return Inertia::render('Web/MisionVision', [
            'title' => 'Misión y Visión'
        ]);
    }

    public function historia(): Response
    {
        return Inertia::render('Web/Historia', [
            'title' => 'Nuestra Historia'
        ]);
    }

    public function orientacion(): Response
    {
        return Inertia::render('Web/Orientacion', [
            'title' => 'Orientación'
        ]);
    }
    public function capellania(): Response
    {
        return Inertia::render('Web/Capellania', [
            'title' => 'Capellania'
        ]);
    }

    public function coordinacion(): Response
    {
        return Inertia::render('Web/Coordinacion', [
            'title' => 'Coordinación'
        ]);
    }
    public function psicologia(): Response
    {
        return Inertia::render('Web/Psicologia', [
            'title' => 'Departamento de Psicología'
        ]);
    }

    public function actividades(): Response
    {
        return Inertia::render('Web/Actividades', [
            'title' => 'Actividades'
        ]);
    }

    public function admision(): Response
    {
        return Inertia::render('Web/Admision', [
            'title' => 'Admisión'
        ]);
    }
    public function matricula(): Response
    {
        return Inertia::render('Web/Matricula', [
            'title' => 'Matrícula'
        ]);
    }
    public function contacto(): Response
    {
        return Inertia::render('Web/Contacto', [
            'title' => 'Contacto'
        ]);
    }
    public function servicio(): Response
    {
        return Inertia::render('Web/Servicio', [
            'title' => 'Nuestros Servicios'
        ]);
    }
}

