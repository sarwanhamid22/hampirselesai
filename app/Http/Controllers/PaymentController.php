<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\Students;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Pembayaran";
        $payments = Payments::all();
        return view('payments.index', compact('payments', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Students::with('class')->get();
        $title = "Tambah Pembayaran";
        return view('payments.create', compact('students', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year' => 'required|string',
            'payment_type' => 'required|array',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
        ]);
    
        try {
            Payments::create($validatedData);
        } catch (\Exception $e) {
            // Handle any other exceptions here
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create payment']);
        }
        
        return redirect()->route('listPayments')->with('success', 'Pembayaran Berhasil Ditambahkan');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Payments $payment)
    {
        $title = "Detail Pembayaran";
        return view('payments.show', compact('payment', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payments $payment)
    {
        $title = "Edit Pembayaran";
        $students = Students::with('class')->get();
        return view('payments.edit', compact('payment', 'students', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payments $payment)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year' => 'required|string',
            'payment_type' => 'required|array',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'status'             => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        try {
            $payment->update($validatedData);
        } catch (\Exception $e) {
            // Handle any other exceptions here
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update payment']);
        }
        
        return redirect()->route('listPayments')->with('success', 'Pembayaran Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payment)
    {
        $payment->delete();
        return redirect()->route('listPayments')->with('success', 'Pembayaran Berhasil Dihapus');
    }
}

