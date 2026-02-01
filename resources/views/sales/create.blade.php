@extends('adminlte::page')

@section('title', 'ขายสินค้า (POS)')

@section('content_header')
    <h1><i class="fas fa-cash-register mr-2"></i>ขายสินค้า (POS)</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-barcode mr-2"></i>ค้นหาสินค้า</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>สแกนบาร์โค้ด / ค้นหาสินค้า</label>
                            <input type="text" id="product-search" class="form-control form-control-lg" placeholder="สแกนบาร์โค้ดหรือพิมพ์ชื่อสินค้า..." autofocus>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>เลือกสมาชิก (ไม่บังคับ)</label>
                            <input type="text" id="member-search" class="form-control" placeholder="ค้นหาสมาชิก...">
                            <input type="hidden" id="member-id">
                            <div id="member-info" class="mt-2"></div>
                        </div>
                    </div>
                </div>
                <div id="search-results" class="list-group" style="position: absolute; z-index: 1000; width: 45%;"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-shopping-cart mr-2"></i>รายการสินค้า</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover" id="cart-table">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>สินค้า</th>
                            <th width="120">ราคา</th>
                            <th width="150">จำนวน</th>
                            <th width="120">รวม</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="cart-items">
                        <tr id="empty-cart">
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                <p>ยังไม่มีสินค้าในตะกร้า</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calculator mr-2"></i>สรุปยอด</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>รวมเป็นเงิน</span>
                    <strong id="subtotal">฿0.00</strong>
                </div>
                <div class="form-group">
                    <label>ส่วนลด</label>
                    <div class="input-group">
                        <input type="number" id="discount" class="form-control" value="0" min="0" step="0.01">
                        <div class="input-group-append">
                            <span class="input-group-text">บาท</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="h5">ยอดสุทธิ</span>
                    <strong class="h4 text-success" id="total">฿0.00</strong>
                </div>
                <div class="form-group">
                    <label>รับเงิน</label>
                    <div class="input-group">
                        <input type="number" id="paid-amount" class="form-control form-control-lg" min="0" step="0.01">
                        <div class="input-group-append">
                            <span class="input-group-text">บาท</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="h5">เงินทอน</span>
                    <strong class="h4 text-info" id="change">฿0.00</strong>
                </div>
                <div class="form-group">
                    <label>วิธีชำระเงิน</label>
                    <select id="payment-method" class="form-control">
                        <option value="cash">เงินสด</option>
                        <option value="transfer">โอนเงิน</option>
                        <option value="credit">เครดิต</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>หมายเหตุ</label>
                    <textarea id="note" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" id="btn-checkout" class="btn btn-success btn-lg btn-block" disabled>
                    <i class="fas fa-check mr-2"></i>บันทึกการขาย
                </button>
                <button type="button" id="btn-clear" class="btn btn-outline-danger btn-block">
                    <i class="fas fa-trash mr-2"></i>ล้างรายการ
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-primary btn-block quick-cash" data-amount="20">฿20</button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-primary btn-block quick-cash" data-amount="50">฿50</button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-primary btn-block quick-cash" data-amount="100">฿100</button>
                    </div>
                    <div class="col-4 mt-2">
                        <button type="button" class="btn btn-outline-primary btn-block quick-cash" data-amount="500">฿500</button>
                    </div>
                    <div class="col-4 mt-2">
                        <button type="button" class="btn btn-outline-primary btn-block quick-cash" data-amount="1000">฿1000</button>
                    </div>
                    <div class="col-4 mt-2">
                        <button type="button" class="btn btn-outline-success btn-block" id="exact-amount">พอดี</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    #search-results .list-group-item { cursor: pointer; }
    #search-results .list-group-item:hover { background-color: #f8f9fa; }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let cart = [];
    let searchTimeout;

    // Product Search
    document.getElementById('product-search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const term = this.value.trim();
        if (term.length < 2) {
            document.getElementById('search-results').innerHTML = '';
            return;
        }
        searchTimeout = setTimeout(() => searchProducts(term), 300);
    });

    document.getElementById('product-search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const barcode = this.value.trim();
            if (barcode) {
                searchByBarcode(barcode);
            }
        }
    });

    function searchProducts(term) {
        fetch(`{{ route('products.search') }}?term=${encodeURIComponent(term)}`)
            .then(res => res.json())
            .then(products => {
                const results = document.getElementById('search-results');
                results.innerHTML = products.map(p => `
                    <a href="#" class="list-group-item list-group-item-action" onclick="addToCart(${JSON.stringify(p).replace(/"/g, '&quot;')}); return false;">
                        <strong>${p.code}</strong> - ${p.name}
                        <span class="float-right text-success">฿${parseFloat(p.selling_price).toFixed(2)}</span>
                        <br><small class="text-muted">คงเหลือ: ${p.stock_quantity} ${p.unit}</small>
                    </a>
                `).join('');
            });
    }

    function searchByBarcode(barcode) {
        fetch(`{{ route('products.barcode') }}?barcode=${encodeURIComponent(barcode)}`)
            .then(res => res.json())
            .then(product => {
                if (product.error) {
                    Swal.fire({ icon: 'warning', title: 'ไม่พบสินค้า', text: 'ไม่พบสินค้าที่มีบาร์โค้ดนี้', timer: 2000 });
                } else {
                    addToCart(product);
                }
                document.getElementById('product-search').value = '';
                document.getElementById('search-results').innerHTML = '';
            });
    }

    function addToCart(product) {
        const existing = cart.find(item => item.product_id === product.id);
        if (existing) {
            if (existing.quantity >= product.stock_quantity) {
                Swal.fire({ icon: 'warning', title: 'สินค้าไม่เพียงพอ', timer: 2000 });
                return;
            }
            existing.quantity++;
        } else {
            cart.push({
                product_id: product.id,
                code: product.code,
                name: product.name,
                unit_price: parseFloat(product.selling_price),
                quantity: 1,
                stock: product.stock_quantity,
                unit: product.unit
            });
        }
        document.getElementById('product-search').value = '';
        document.getElementById('search-results').innerHTML = '';
        renderCart();
    }

    function renderCart() {
        const tbody = document.getElementById('cart-items');
        if (cart.length === 0) {
            tbody.innerHTML = `<tr id="empty-cart"><td colspan="6" class="text-center text-muted py-4">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i><p>ยังไม่มีสินค้าในตะกร้า</p></td></tr>`;
            document.getElementById('btn-checkout').disabled = true;
        } else {
            tbody.innerHTML = cart.map((item, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td><strong>${item.name}</strong><br><small class="text-muted">${item.code}</small></td>
                    <td>฿${item.unit_price.toFixed(2)}</td>
                    <td>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQty(${i}, -1)">-</button>
                            </div>
                            <input type="number" class="form-control text-center" value="${item.quantity}" min="1" max="${item.stock}" onchange="setQty(${i}, this.value)">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQty(${i}, 1)">+</button>
                            </div>
                        </div>
                    </td>
                    <td class="text-success">฿${(item.unit_price * item.quantity).toFixed(2)}</td>
                    <td><button class="btn btn-sm btn-danger" onclick="removeItem(${i})"><i class="fas fa-times"></i></button></td>
                </tr>
            `).join('');
            document.getElementById('btn-checkout').disabled = false;
        }
        calculateTotal();
    }

    function changeQty(index, delta) {
        const item = cart[index];
        const newQty = item.quantity + delta;
        if (newQty < 1) return removeItem(index);
        if (newQty > item.stock) {
            Swal.fire({ icon: 'warning', title: 'สินค้าไม่เพียงพอ', timer: 2000 });
            return;
        }
        item.quantity = newQty;
        renderCart();
    }

    function setQty(index, value) {
        const item = cart[index];
        const qty = parseInt(value) || 1;
        if (qty > item.stock) {
            Swal.fire({ icon: 'warning', title: 'สินค้าไม่เพียงพอ', timer: 2000 });
            item.quantity = item.stock;
        } else {
            item.quantity = Math.max(1, qty);
        }
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function calculateTotal() {
        const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const total = subtotal - discount;
        const paid = parseFloat(document.getElementById('paid-amount').value) || 0;
        const change = paid - total;

        document.getElementById('subtotal').textContent = '฿' + subtotal.toFixed(2);
        document.getElementById('total').textContent = '฿' + total.toFixed(2);
        document.getElementById('change').textContent = '฿' + (change > 0 ? change.toFixed(2) : '0.00');
    }

    document.getElementById('discount').addEventListener('input', calculateTotal);
    document.getElementById('paid-amount').addEventListener('input', calculateTotal);

    document.querySelectorAll('.quick-cash').forEach(btn => {
        btn.addEventListener('click', function() {
            const current = parseFloat(document.getElementById('paid-amount').value) || 0;
            document.getElementById('paid-amount').value = current + parseInt(this.dataset.amount);
            calculateTotal();
        });
    });

    document.getElementById('exact-amount').addEventListener('click', function() {
        const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        document.getElementById('paid-amount').value = (subtotal - discount).toFixed(2);
        calculateTotal();
    });

    document.getElementById('btn-clear').addEventListener('click', function() {
        Swal.fire({
            title: 'ล้างรายการ?',
            text: 'ต้องการล้างรายการสินค้าทั้งหมดใช่หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ล้าง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                document.getElementById('member-id').value = '';
                document.getElementById('member-info').innerHTML = '';
                document.getElementById('discount').value = 0;
                document.getElementById('paid-amount').value = '';
                document.getElementById('note').value = '';
                renderCart();
            }
        });
    });

    // Member Search
    let memberTimeout;
    document.getElementById('member-search').addEventListener('input', function() {
        clearTimeout(memberTimeout);
        const term = this.value.trim();
        if (term.length < 2) return;
        memberTimeout = setTimeout(() => {
            fetch(`{{ route('members.search') }}?term=${encodeURIComponent(term)}`)
                .then(res => res.json())
                .then(members => {
                    if (members.length > 0) {
                        const m = members[0];
                        document.getElementById('member-id').value = m.id;
                        document.getElementById('member-info').innerHTML = `
                            <span class="badge badge-success"><i class="fas fa-user mr-1"></i>${m.member_code} - ${m.name}</span>
                            <button type="button" class="btn btn-sm btn-link text-danger" onclick="clearMember()">ยกเลิก</button>
                        `;
                    }
                });
        }, 300);
    });

    function clearMember() {
        document.getElementById('member-id').value = '';
        document.getElementById('member-search').value = '';
        document.getElementById('member-info').innerHTML = '';
    }

    // Checkout
    document.getElementById('btn-checkout').addEventListener('click', function() {
        const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const total = subtotal - discount;
        const paid = parseFloat(document.getElementById('paid-amount').value) || 0;

        if (paid < total) {
            Swal.fire({ icon: 'error', title: 'เงินไม่เพียงพอ', text: 'กรุณารับเงินให้ครบ' });
            return;
        }

        const data = {
            member_id: document.getElementById('member-id').value || null,
            items: cart.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity,
                unit_price: item.unit_price
            })),
            discount: discount,
            paid_amount: paid,
            payment_method: document.getElementById('payment-method').value,
            note: document.getElementById('note').value
        };

        fetch('{{ route('sales.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกการขายสำเร็จ!',
                    html: `เลขที่: <strong>${result.sale.invoice_number}</strong><br>ยอดรวม: <strong>฿${parseFloat(result.sale.total).toFixed(2)}</strong>`,
                    showCancelButton: true,
                    confirmButtonText: 'พิมพ์ใบเสร็จ',
                    cancelButtonText: 'ปิด'
                }).then((r) => {
                    if (r.isConfirmed) {
                        window.open(`/sales/${result.sale.id}/receipt`, '_blank');
                    }
                    cart = [];
                    clearMember();
                    document.getElementById('discount').value = 0;
                    document.getElementById('paid-amount').value = '';
                    document.getElementById('note').value = '';
                    renderCart();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: result.message });
            }
        });
    });

    renderCart();
</script>
@stop
