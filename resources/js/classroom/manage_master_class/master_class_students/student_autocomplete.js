$(function() {
    $("#student_name").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ route('users.search') }}",
                dataType: "json",
                data: {
                    term: request.term // Parameter yang dikirim oleh jQuery UI Autocomplete
                },
                success: function(data) {
                    response(data);
                },
                error: function(xhr) {
                    console.error(xhr);
                    response([]);
                }
            });
        },
        minLength: 2, // Minimum karakter sebelum pencarian dilakukan
        select: function(event, ui) {
            // Set nilai hidden input dengan ID siswa
            $("#student_id").val(ui.item.value);
            // Optional: Anda bisa mengubah nilai input menjadi nama siswa saja
            // $("#student_name").val(ui.item.label.split(' - ')[1]);
            return false; // Prevent default behavior
        },
        focus: function(event, ui) {
            // Prevent value dari input diubah saat navigasi dropdown
            $("#student_name").val(ui.item.label);
            return false;
        }
    })
    .autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
            .append("<div>" + item.label + "</div>")
            .appendTo(ul);
    };
});