@component('mail::message')

<small>{{\Carbon\Carbon::now()->format('F d, Y')}}</small>


# No-Frills Fundraiser Tax Receipt

Dear ASFS Supporter,

Thank you for supporting the ASFS PTA! Your generous donation of ${{$item['amount']}} is greatly appreciated. Your donation helps fund wonderful educational programs, community building events, classroom supplies and teacher appreciation events. 

Please note, the ASFS PTA is a 501(c)(3) nonprofit organization. Our Tax I.D. is 541812691. Donations to the No-Frills Fundraiser are tax-deductible to the extent allowed by law. Please retain a copy of this form, which will serve as your donation receipt. No goods or services were received in exchange for this donation. 

Thank you again for your support of the Arlington Science Focus PTA.

Sincerely,

Kristi Tsiopanas <br> VP Fundraising, Arlington Science Focus School PTA



<small>Thank you for your support! To make your donations go even further, please consider asking your employer if they will double or even triple your donation. Contact PTA Fundraising Chair Kristi Tsiopanas (ktsiopanas@gmail.com) if you need help with this process.</small>


@endcomponent
