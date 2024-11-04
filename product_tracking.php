<style>
    html {
    scroll-behavior: smooth;
    }
    .hentry .entry-header .entry-title:after {
    background-color: #333333;
    content: "";
    display: block;
    height: 2px;
    margin: 1rem 0 0;
    width: 5rem;
}

    .tracking_container {
    display: flex;
    padding-left: 0px;
    min-height:650px;
    }

    .left {
        width: 30%;
        padding: 20px;
        padding-left: 0;
    }

    .middle {
    width: 40%;
    padding: 20px;
    text-align: center;
    padding-right:40px;
    padding-top:0;
    }
    .middle > img{
        border-radius: 10px;
        margin-bottom:10px;
    }
    .right {
    width: 30%;
    padding:0px 30px;
    border-left: 16px solid transparent;
    position: relative;
    }

    .right::before {
    content: '';
    position: absolute;
    top: 0;
    left: -16px;
    height: 100%;
    width: 16px;
    background-color: green;
    animation: fillBorder 2s forwards;
    border-radius:20px;
    }

    @keyframes fillBorder {
    0% {
        height: 0;
    }
    100% {
        height: 100%;
    }
    }


    .status {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    }

    .status .icon {
    margin-right: 10px;
    }

    .status .text {
    font-weight: bold;
    }

    .tracking-id {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    }

    .tracking-id .icon {
    margin-right: 5px;
    }

    .tracking-id .text {
    font-size: 16px;
    }

    .tracking-list {
    list-style: none;
    padding: 0;
    }

    .tracking-list li {
    margin-bottom: 20px;
    position: relative;
    }

    .tracking-list li .location {
    font-weight: bold;
    }

    .tracking-list li .date {
    font-size: 14px;
    color: #666;
    }

    .delivered {
    background-color: #e0ffe0;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
    }

    .delivered .icon {
    margin-right: 10px;
    }

    .delivered .text {
    font-weight: bold;
    }

    .delivered .date {
    font-size: 14px;
    color: #666;
    }

    .button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    }

    .button:hover {
    background-color: #3e8e41;
    }

    .more-options {
    margin-top: 20px;
    }

    .indicator {
        width: 8px;
        height: 8px;
        background: white;
        position: absolute;
        left: -42px;
        border-radius: 100px;
        top: 6px;
    }
    .indicator.active {
        width: 60px;
        height: 60px;
        left: -69px;
        border: 10px solid green;
        background: green;
        display: flex;
        align-items: center;
        justify-content: center;
        top: -15px;
    }
    .indicator.active img {
        border: 2px solid white;
        padding: 10px;
        border-radius: 100%;
        z-index: 9;
    }


    form#fedex-tracking-form {
        padding-bottom: 30px;
        margin-top: -20px;
    }

    form#fedex-tracking-form button {
        border: none;
        background: green;
        padding: 10px 20px;
        font-size: 20px;
        color: white;
        border-radius: 5px;
        height: 47px;
        margin-top: -3px;
        margin-left:10px;
    }

    form#fedex-tracking-form input {
        margin: 10px 0px;
        background: #eaeaea63;
        border-radius: 6px;
        padding: 10px;
        border: none;
        width: 196px;
        border: 1px solid #0000000f;
        font-size:16px;
    }
    form#fedex-tracking-form label {
        font-weight: 500;
        color:black;
    }

    @media only screen and (max-width: 768px) {
        form#fedex-tracking-form {
        padding: 20px;
    }
    .tracking_container {
        display: flex;
        padding-left: 0px;
        flex-direction: column;
        padding: 20px;
    }
    .middle {
        padding: 0;
        text-align: center;
        margin-bottom:20px;

    }

    .tracking_container  > div{
        width:100%;
    }

    .hentry .entry-header .entry-title {
        margin-top: 0.3125rem;
        font-size: 1.438rem;
        letter-spacing: 0.025em;
        line-height: 1.2;
        margin-bottom: 3.125rem;
        text-align: center;
    }
    .hentry .entry-header .entry-title:after {
        background-color: #333333;
        content: "";
        display: block;
        height: 2px;
        margin: 2.6875rem auto;
        width: 5rem;
    }
    div#tracking-result {
        padding-left: 20px;
    }

    }

    .indicator.active::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 400%;
    height: 400%;
    background-color: green;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
    animation: ripple 2.5s ease-out .3s infinite;
    }




    @keyframes ripple {
    0% {
        transform: translate(-50%, -50%) scale(0);
        opacity: 1;
    }
    50% {
        opacity: 0;
    }
    100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0;
    }
    
    }

    form input:-webkit-autofill {
        transition: background-color 5000s ease-in-out 0s;
        -webkit-text-fill-color: black !important;
    }


    /* Shipment Info  */


    .shipment-container {
        margin: 0 auto;
        background-color: #fff;
        padding: 0;
        border-radius: 10px;
        padding-top: 60px;
    }

    .shipment-title {
        font-size: 2em;
        margin-bottom: 20px;
    }

    .shipment-section {
        margin-bottom: 30px;
    }

    .shipment-section h2 {
        display: flex;
        align-items: center;
        font-size: 1.5em;
        margin-bottom: 10px;
    }

    .shipment-icon {
        font-size: 1.2em;
        margin-right: 10px;
    }

    .shipment-overview-icon::before {
        content: '\1F4E6'; /* Unicode for box */
        margin-right: 10px;
    }

    .shipment-services-icon::before {
        content: '\1F69A'; /* Unicode for truck */
        margin-right: 10px;
    }

    .shipment-package-details-icon::before {
        content: '\1F4E6'; /* Unicode for package */
        margin-right: 10px;
    }

    .shipment-details {
        background-color: #f8f8f859;
        border-radius: 5px;
        padding: 10px;
        border: 1px solid #e0e0e063;
    }

    .shipment-detail {
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e09e;
    }

    .shipment-detail:last-child {
        border-bottom: none;
    }

    .shipment-label {
        font-weight: bold;
        text-transform: uppercase;
        color: #555;
    }

    .shipment-value {
        color: #000;
    }

    .shipment-back-to-top {
        text-align: right;
    }

    .shipment-back-to-top a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
    }

    .shipment-back-to-top a:hover {
        text-decoration: underline;
    }
    @media only screen and (max-width: 768px) {
        .shipment-container {
            padding: 20px;
        }
    }

</style>

<form id="fedex-tracking-form" method="post">
    <label for="tracking-number">Enter your FedEx tracking number:</label>
    <input type="text" id="tracking-number" name="tracking_number" required>
    <button type="submit">Track</button>
</form>

<div id="tracking-result"></div>
<div class="tracking_container"></div>

 <!-- Shipment Info   -->
<div class="shipment-container" id="shipment-container">
</div>


<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#fedex-tracking-form').on('submit', function(event) {
            event.preventDefault();
            var trackingNumber = $('#tracking-number').val();
            console.log('before ajax')
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'track_fedex_package',
                    tracking_number: trackingNumber,
                },
                beforeSend: function() {
                    $('#tracking-result').html('Tracking...');
                    $('.tracking_container').css('opacity','0.4')
                    $('.shipment-container').css('opacity','0.4')
                },
                success: function(response) {
                    console.log('response : ',response)
                    let api_data = response.data.output.completeTrackResults[0].trackResults[0];
                    if (!api_data.error){
                
                    // Status
                    let status = api_data.latestStatusDetail?.statusByLocale ?? 'Unknown status';
            
                    // Estimated Delivery Time
                    let estimatedDeliveryTime = '';
                    if (api_data.estimatedDeliveryTimeWindow?.window?.begins) {
                        let estimatedDeliveryTimeWindow = api_data.estimatedDeliveryTimeWindow;
            
                        let begins = new Date(estimatedDeliveryTimeWindow.window.begins);
                        let ends = new Date(estimatedDeliveryTimeWindow.window.ends);
            
                        let optionsTime = { hour: '2-digit', minute: '2-digit', hour12: true };
            
                        let formattedWeekday = begins.toLocaleDateString('en-GB', { weekday: 'long' });
                        let formattedDate = begins.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
            
                        let formattedBeginTime = begins.toLocaleTimeString('en-US', optionsTime);
                        let formattedEndTime = ends.toLocaleTimeString('en-US', optionsTime);
            
                        estimatedDeliveryTime = `SCHEDULED DELIVERY DATE :<br><h4>${formattedWeekday}</h4>
                        ${formattedDate} by end of day
                        Estimated between
                        ${formattedBeginTime} - ${formattedEndTime}`;
                    }
            
                    // Tracking Number
                    let trackingNumber = $('#tracking-number').val();
            
                    // Standard Transit
                    let standardTransit = '';
                    if (api_data.dateAndTimes && api_data.dateAndTimes.length > 0) {
                        standardTransit = formatDateTime(
                            api_data.dateAndTimes.find(event => event.type === "COMMITMENT")?.dateTime || 
                            api_data.dateAndTimes.find(event => event.type === "ACTUAL_DELIVERY")?.dateTime
                        ) ?? '';
                    }
            
                    // Ship Date
                    let shipDate = getFormattedEventDate('PU') ?? 'Unknown ship date';
            
                    // Service
                    let services = api_data.serviceDetail?.description ?? 'Unknown service';
            
                    // Weight (converted to KG and LB)
                    let weightLb = api_data.packageDetails?.weightAndDimensions?.weight?.find(event => event.unit === "LB")?.value ?? 0;
                    let weightKg = (weightLb * 0.453592).toFixed(2); // Convert lbs to kgs
                    let weight = `${weightLb} lbs / ${weightKg} kgs`;
            
                    // Total Pieces
                    let totalPieces = api_data.packageDetails?.count ?? 0;
            
                    // Packages
                    let packages = api_data.packageDetails?.packagingDescription?.description ?? 'Unknown packaging';
            
                    // Helper function to get formatted event date
                    function getFormattedEventDate(eventType) {
                        let event = api_data.scanEvents?.find(event => event.eventType === eventType);
                        return event ? formatDateTime(event.date) : '';
                    }
            
                    // Status based on scan events
                    function getStatus() {
                        if (api_data.scanEvents?.find(event => event.eventType === 'DL')) {
                            return 'dl';
                        }
                        if (api_data.scanEvents?.find(event => event.eventType === 'OD')) {
                            return 'od';
                        }
                        if (api_data.scanEvents?.find(event => event.eventType === 'IT')) {
                            return 'it';
                        }
                        if (api_data.scanEvents?.find(event => event.eventType === 'PU')) {
                            return 'it';
                        }
                        if (api_data.scanEvents?.find(event => event.eventType === 'OC')) {
                            return 'dp';
                        }
                        return 'Unknown status';
                    }
            
                    // Dates for different stages
                    let formDate = getFormattedEventDate('OC') ?? 'Unknown date';
                    let pickUpDate = getFormattedEventDate('PU') ?? 'Unknown date';
                    let onTheWayDate = getFormattedEventDate('IT') ?? 'Unknown date';
                    let outForDeliveryDate = getFormattedEventDate('OD') ?? 'Unknown date';
                    let delivaryDate = getFormattedEventDate('DL') ?? 'Unknown date';
            
                    // Helper function to format date and time
                    function formatDateTime(dateStr) {
                        const date = new Date(dateStr);
                        const month = padZero(date.getMonth() + 1);
                        const day = padZero(date.getDate());
                        const year = date.getFullYear();
                        const hours = date.getHours();
                        const minutes = date.getMinutes();
                        const ampm = hours >= 12 ? 'PM' : 'AM';
                        const hours12 = hours % 12 || 12;
            
                        return `${day}/${month}/${year} ${padZero(hours12)}:${padZero(minutes)} ${ampm}`;
                    }
            
                    // Helper function to add leading zero
                    function padZero(num) {
                        return (num < 10 ? '0' : '') + num;
                    }
            
                    // Location
                    function getFormattedEventLocation(eventType) {
                        let event = api_data.scanEvents?.find(event => event.eventType === eventType);
                        return event ? `${event.scanLocation?.city ?? 'Unknown city'}, ${event.scanLocation?.stateOrProvinceCode ?? 'Unknown state'}` : '';
                    }
            
                    let pickUpLocation = getFormattedEventLocation('PU') ?? 'Unknown pickup location';
                    let onTheWayLocation = getFormattedEventLocation('IT') ?? 'Unknown location';
                    let outForDeliveryLocation = getFormattedEventLocation('OD') ?? 'Unknown location';
                    let delivaryLocation = getFormattedEventLocation('DL') ?? 'Unknown location';
            
                    let formLocation = api_data.scanEvents?.find(event => event.eventType === 'OC')?.scanLocation?.countryName ?? 'Unknown country';
            
                    console.log(response);
                    console.log('api_data', api_data);
                    console.log('status', status);
            
                    const DELIVERY_IMAGE = '/wp-content/uploads/2024/07/woman-hand-accepting-delivery-boxes-from-deliveryman-1-1024x683-1.jpg';
                    const DEFAULT_IMAGE = '/wp-content/uploads/2024/07/656fad84d9a1ee00769d0aca_how-do-experts-like-fedex-plan-delivery-routes-HERO.webp';
            
                    function getDeliveryImage() {
                        const status = getStatus();
                        if (status === 'dl') {
                            return DELIVERY_IMAGE;
                        } else {
                            return DEFAULT_IMAGE;
                        }
                    }

   

                            // Append HTML 

                            $('.tracking_container').html(`
                                    <div class="left">
                                        <h1>${status}</h1>
                                        <p>${delivaryDate}</p>
                                        <p>${estimatedDeliveryTime}</p>
                                    </div>

                                    <div class="middle">
                                        <img src=${getDeliveryImage()}  alt="Package">
                                        <div class="status">
                                        <div class="icon">&#10004;</div>
                                        <div class="text">${status}</div>
                                        </div>
                                    </div>

                                    <div class="right">

                                        <h3>TRACKING ID</h3>
                                        <div class="tracking-id">
                                        <div class="icon">&#9997;</div>
                                        <div class="text">${$('#tracking-number').val()}</div>
                                        </div>

                                        <ul class="tracking-list">
                                        <li class="dp">
                                            <div class="indicator dp">
                                                    <img src="/wp-content/uploads/2024/07/right-arrow.png" alt="">
                                            </div>
                                            <div class="location">FROM</div>
                                            <div class="date">${formLocation}</div>
                                            <div class="date">Label Created</div>
                                            <div class="date">${formDate}</div>
                                        </li>
                                        <li class="pu">
                                            <div class="indicator pu">
                                                    <img src="/wp-content/uploads/2024/07/right-arrow.png" alt="">
                                            </div>
                                            <div class="location">${pickUpLocation}</div>
                                            <div class="date">${pickUpDate}</div>
                                        </li>
                                        <li class="it">
                                            <div class="indicator it">
                                                <img src="/wp-content/uploads/2024/07/right-arrow.png" alt="">
                                            </div>
                                            <div class="location">ON THE WAY</div>
                                            <div class="date">${onTheWayLocation}</div>
                                            <div class="date">${onTheWayDate}</div>
                                        </li>
                                        <li class="od">
                                            <div class="indicator od">
                                                    <img src="/wp-content/uploads/2024/07/right-arrow.png" alt="">
                                            </div>
                                            <div class="location">OUT FOR DELIVERY</div>
                                            <div class="date">${outForDeliveryLocation}</div>
                                            <div class="date">${outForDeliveryDate}</div>
                                        </li>
                                        <li class="delivered dl">
                                            <div class="indicator dl">
                                                    <img src="/wp-content/uploads/2024/07/check.png" alt="">
                                            </div>
                                            <div class="icon">&#10004;</div>
                                            <div class="location">DELIVERED</div>
                                            <div class="date">${delivaryLocation}</div>
                                            <div class="date"></div>
                                            <div class="date">${delivaryDate}</div>
                                        </li>
                                        </ul>


                                        <a href="#shipment-container">View Shipment facts</a>
                                    </div>
                            `)
                            $('.shipment-container').html(`
                                    <h1 class="shipment-title">Shipment facts</h1>
                                    <div class="shipment-section">
                                        <h2 class="shipment-icon shipment-overview-icon">Shipment overview</h2>
                                        <div class="shipment-details">
                                            <div class="shipment-detail">
                                                <span class="shipment-label">Tracking Number</span>
                                                <span class="shipment-value">${trackingNumber}</span>
                                            </div>
                                            <div class="shipment-detail">
                                                <span class="shipment-label">Ship Date</span>
                                                <span class="shipment-value">${shipDate}</span>
                                            </div>
                                            <div class="shipment-detail">
                                                <span class="shipment-label">Standard Transit</span>
                                                <span class="shipment-value">${standardTransit}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="shipment-section">
                                        <h2 class="shipment-icon shipment-services-icon">Services</h2>
                                        <div class="shipment-details">
                                            <div class="shipment-detail">
                                                <span class="shipment-label">Service</span>
                                                <span class="shipment-value">${services}</span>
                                            </div>
                                            <div class="shipment-detail">
                                                <span class="shipment-label">Terms</span>
                                                <span class="shipment-value">Shipper</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="shipment-section">
                                        <h2 class="shipment-icon shipment-package-details-icon">Package details</h2>
                                        <div class="shipment-details">
                                            <div class="shipment-detail">
                                                <span class="shipment-label">Weight</span>
                                                <span class="shipment-value">${weight}</span>
                                            </div>
                                            <div class="shipment-detail">
                                                <span class="shipment-label">Total Pieces</span>
                                                <span class="shipment-value">${totalPieces}</span>
                                            </div>
                                            <div class="shipment-detail">
                                                <span class="shipment-label">Packaging</span>
                                                <span class="shipment-value">${packages}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="shipment-back-to-top">
                                        <a href="#">Back to top</a>
                                    </div>
                            `)

                            $(`.indicator.${getStatus()}`).addClass('active');
                            $(`li.${getStatus()}`).nextAll('li').css('opacity', '0.3');
                            $('.tracking_container').css('opacity','1')
                            $('.shipment-container').css('opacity','1')
                            $('#tracking-result').html('');

                    } else{
                        $('#tracking-result').html('<p2>No record of this tracking number can be found at this time, please check the number and try again later. <p2>');
                        $('.tracking_container').html('')
                        $('.shipment-container').html('')
                        console.log('Not Found')
                    }
      
                },
                error: function(xhr, status, error) {
                    $('#tracking-result').html('An error occurred: ' + error);
                }
            });
        });
    });
</script>