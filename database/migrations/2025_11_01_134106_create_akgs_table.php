    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('akgs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('min_age');
            $table->unsignedSmallInteger('max_age');
            $table->unsignedTinyInteger('gender');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akgs');
    }
};
