<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLoanSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('loan_settings', function (Blueprint $table) {
            $table->id();
            $table->string('borrower_name')->default('Borrower');
            $table->string('loan_title')->default('Personal Loan');
            $table->decimal('total_loan', 12, 2)->default(8000.00);
            $table->date('start_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        DB::table('loan_settings')->insert([
            'borrower_name' => 'Borrower',
            'loan_title' => 'Personal Loan',
            'total_loan' => 8000.00,
            'start_date' => now()->toDateString(),
            'notes' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('loan_settings');
    }
}
