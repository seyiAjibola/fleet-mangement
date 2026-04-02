<section>
    <x-admin.header pageTitle="Supplier Details" pageSubTitle="Full supplier profile and registration information." />

    <div class="card" style="display: grid; gap: 18px;">
        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: start; flex-wrap: wrap;">
            <div>
                <h3 style="margin: 0;">{{ $supplier->business_name }}</h3>
                <p style="margin: 6px 0 0; color: var(--muted);">{{ $supplier->business_type }}</p>
            </div>
            <div class="table-actions">
                <a class="button secondary" href="{{ route('admin.suppliers.edit', $supplier) }}">Edit Supplier</a>
                <a class="button secondary" href="{{ route('admin.suppliers.index') }}">Back to Suppliers</a>
            </div>
        </div>

        <div class="card-grid">
            <div class="card">
                <h3>Contact Person</h3>
                <div>{{ $supplier->contact_person ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Contact Number</h3>
                <div>{{ $supplier->phone_number ?: '—' }}</div>
            </div>
            <div class="card">
                <h3>Number of Cars</h3>
                <div>{{ $supplier->vehicles_count }}</div>
            </div>
            <div class="card">
                <h3>Status</h3>
                <div><span class="badge">{{ $supplier->status }}</span></div>
            </div>
        </div>

        <div class="table-card">
            <table>
                <tbody>
                    <tr>
                        <th style="width: 220px;">Company Name</th>
                        <td>{{ $supplier->business_name }}</td>
                    </tr>
                    <tr>
                        <th>CAC No</th>
                        <td>{{ $supplier->cac_no ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>TIN</th>
                        <td>{{ $supplier->tin ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $supplier->email }}</td>
                    </tr>
                    <tr>
                        <th>Location / Address</th>
                        <td>{{ $supplier->city }}{{ $supplier->business_address ? ', ' . $supplier->business_address : '' }}</td>
                    </tr>
                    <tr>
                        <th>Years in Business</th>
                        <td>{{ $supplier->years_in_business }}</td>
                    </tr>
                    <tr>
                        <th>Supplier Tier</th>
                        <td>{{ $supplier->supplier_tier ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Supplier Score</th>
                        <td>{{ $supplier->supplier_score }}</td>
                    </tr>
                    <tr>
                        <th>Instagram Page</th>
                        <td>{{ $supplier->instagram_page ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Website</th>
                        <td>{{ $supplier->website ?: '—' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
