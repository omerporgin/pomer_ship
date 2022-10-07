import $ from "jquery";

export class VendorPackages{
    constructor() {

        this.events();
    }

    events(){
        $(document).on('click', '.print_package', function (){
            var id = $(this).attr('data-id');

            var w = window.open('yourfile.txt'); //Required full file path.
            w.print();
        })
    }
}
