<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|max:2048', // 2MB
            'primary_color' => 'nullable|string|max:7',
            'whatsapp_phone_number_id' => 'nullable|string',
            'whatsapp_business_account_id' => 'nullable|string',
            'whatsapp_access_token' => 'nullable|string',
        ]);

        if ($request->has('app_name')) {
            Settings::set('app_name', $request->app_name, 'branding');
        }

        if ($request->has('primary_color')) {
            Settings::set('primary_color', $request->primary_color, 'branding');
        }

        if ($request->has('whatsapp_phone_number_id')) {
            Settings::set('whatsapp_phone_number_id', $request->whatsapp_phone_number_id, 'integrations');
        }
        
        if ($request->has('whatsapp_business_account_id')) {
            Settings::set('whatsapp_business_account_id', $request->whatsapp_business_account_id, 'integrations');
        }
        
        if ($request->has('whatsapp_access_token')) {
            Settings::set('whatsapp_access_token', $request->whatsapp_access_token, 'integrations');
        }

        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('branding', 'public');
            Settings::set('app_logo', $path, 'branding');
        }

        return back()->with('success', 'Branding settings updated successfully.');
    }

    public function testWhatsApp(Request $request) 
    {
        $service = new \App\Services\WhatsAppService();
        
        if (!$service->isConfigured()) {
            return back()->with('error', 'Please configure WhatsApp credentials first.');
        }

        // Send a simple "hello_world" template (standard test template in Meta)
        // Need a recipient phone number. For test, use the user's phone or a hardcoded test number from input
        $recipient = $request->input('test_phone');
        
        if (!$recipient) {
             return back()->with('error', 'Please provide a test phone number.');
        }

        $result = $service->sendTemplate($recipient, 'hello_world', 'en_US');

        if ($result) {
            return back()->with('success', "Test message sent to $recipient!");
        } else {
            return back()->with('error', 'Failed to send test message. Check logs.');
        }
    }
}
