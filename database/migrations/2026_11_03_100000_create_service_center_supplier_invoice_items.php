<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLE = 'service_center_supplier_invoice_items';

    public function up(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('invoice_id');
                $table->unsignedBigInteger('inventory_item_id');
                $table->unsignedInteger('quantity');
                $table->unsignedInteger('received_quantity')->default(0);
                $table->decimal('unit_cost', 12, 2);
                $table->timestamps();
            });
        }

        $this->ensureForeignKey(self::TABLE, 'invoice_id', 'scii_invoice_fk', 'service_center_supplier_invoices');
        $this->ensureForeignKey(self::TABLE, 'inventory_item_id', 'scii_inventory_fk', 'service_center_inventory');
        $this->ensureIndex(self::TABLE, ['invoice_id', 'inventory_item_id'], 'scii_invoice_item_uq', true);
        $this->ensureIndex(self::TABLE, ['invoice_id', 'received_quantity'], 'scii_invoice_received_idx');

        $movementTable = 'service_center_inventory_movements';

        if (! Schema::hasColumn($movementTable, 'supplier_invoice_item_id')) {
            Schema::table($movementTable, function (Blueprint $table) {
                $table->unsignedBigInteger('supplier_invoice_item_id')->nullable()->after('supplier_id');
            });
        }

        $this->ensureForeignKey($movementTable, 'supplier_invoice_item_id', 'scim_invoice_item_fk', self::TABLE, 'nullOnDelete');
        $this->ensureIndex($movementTable, ['supplier_invoice_item_id', 'created_at'], 'scim_invoice_item_created_idx');
    }

    private function ensureForeignKey(
        string $tableName,
        string $column,
        string $constraintName,
        string $referencedTable,
        string $onDelete = 'cascadeOnDelete'
    ): void {
        $foreignKeys = collect(Schema::getForeignKeys($tableName));
        $exists = $foreignKeys->contains(fn (array $key) =>
            ($key['name'] ?? null) === $constraintName
            || in_array($column, $key['columns'] ?? [], true)
        );

        if ($exists) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($column, $constraintName, $referencedTable, $onDelete) {
            $foreign = $table->foreign($column, $constraintName)->references('id')->on($referencedTable);

            if ($onDelete === 'nullOnDelete') {
                $foreign->nullOnDelete();
            } else {
                $foreign->cascadeOnDelete();
            }
        });
    }

    private function ensureIndex(string $tableName, array $columns, string $indexName, bool $unique = false): void
    {
        $indexes = collect(Schema::getIndexes($tableName));
        $exists = $indexes->contains(fn (array $index) =>
            ($index['name'] ?? null) === $indexName
            || (($index['columns'] ?? []) === $columns && (bool) ($index['unique'] ?? false) === $unique)
        );

        if ($exists) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($columns, $indexName, $unique) {
            if ($unique) {
                $table->unique($columns, $indexName);
            } else {
                $table->index($columns, $indexName);
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('service_center_inventory_movements')
            && Schema::hasColumn('service_center_inventory_movements', 'supplier_invoice_item_id')) {
            Schema::table('service_center_inventory_movements', function (Blueprint $table) {
                $table->dropForeign('scim_invoice_item_fk');
                $table->dropIndex('scim_invoice_item_created_idx');
                $table->dropColumn('supplier_invoice_item_id');
            });
        }

        Schema::dropIfExists(self::TABLE);
    }
};
