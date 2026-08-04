<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goto_uploaded_reports', function (Blueprint $table): void {
            $table->id();
            $table->string('merchant_id')->nullable()->index();
            $table->string('event_type')->index();
            $table->timestamp('event_created_at')->index();
            $table->string('source_file')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goto_uploaded_reports');
    }
};
