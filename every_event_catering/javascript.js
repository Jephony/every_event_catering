function openModal(id){document.getElementById(id).classList.add('show')}
function closeModal(id){document.getElementById(id).classList.remove('show')}
let bookingCart=JSON.parse(localStorage.getItem('everyEventCart')||'[]');
const savedBookingDetails=JSON.parse(localStorage.getItem('everyEventBooking')||'null');
let bookingDetails=Array.isArray(savedBookingDetails)?savedBookingDetails:(savedBookingDetails?[savedBookingDetails]:[]);
let selectedPackage=JSON.parse(localStorage.getItem('everyEventSelectedPackage')||'null');
let loggedInUser=JSON.parse(localStorage.getItem('everyEventUser')||'null');

function handleAuthClick(event){
    event.preventDefault();
    if(loggedInUser){
        logoutUser();
        return;
    }
    openModal('registerModal');
}

function loginUser(event){
    event.preventDefault();
    loggedInUser={
        name:document.getElementById('loginEmail').value.trim().split('@')[0],
        email:document.getElementById('loginEmail').value.trim()
    };
    localStorage.setItem('everyEventUser',JSON.stringify(loggedInUser));
    document.body.classList.remove('auth-required');
    closeModal('registerModal');
    renderAuthState();
    showMessage('Welcome, '+loggedInUser.name+'!');
    event.target.reset();
}

function logoutUser(){
    loggedInUser=null;
    localStorage.removeItem('everyEventUser');
    document.body.classList.add('auth-required');
    renderAuthState();
    openModal('registerModal');
    showMessage('You have been logged out.');
}

function renderAuthState(){
    const authLink=document.getElementById('authLink');
    if(!authLink){return}
    authLink.textContent=loggedInUser?'LOG OUT':'REGISTER/LOGIN';
    authLink.title=loggedInUser?'Log out':'Register or log in';
}

function searchSite(event){
    event.preventDefault();
    const query=document.getElementById('siteSearch').value.trim().toLowerCase();
    const message=document.getElementById('searchMessage');
    document.querySelectorAll('.search-match, .package-search-match').forEach(element=>{
        element.classList.remove('search-match','package-search-match');
    });
    if(!query){
        message.textContent='Type a word to search.';
        return;
    }
    const packageCards=[...document.querySelectorAll('.price-card')];
    const packageMatch=packageCards.find(card=>card.textContent.toLowerCase().includes(query));
    if(packageMatch){
        showPage(null,'pricing');
        packageMatch.classList.add('package-search-match');
        message.textContent='Package found: '+packageMatch.querySelector('h3').textContent.replace(/\s+/g,' ').trim()+'.';
        return;
    }
    const sections=[...document.querySelectorAll('main > section')];
    const matches=sections.filter(section=>section.textContent.toLowerCase().includes(query));
    if(!matches.length){
        message.textContent='No results found for "'+query+'".';
        return;
    }
    const section=matches[0];
    showPage(null,section.id);
    section.classList.add('search-match');
    message.textContent=matches.length+' section'+(matches.length===1?'':'s')+' found for "'+query+'".';
}

function addToCart(name,price){
    const existingItem=bookingCart.find(item=>item.name===name);
    if(existingItem){
        existingItem.quantity+=1;
    }else{
        bookingCart.push({name,price,quantity:1});
    }
    bookingDetails=[];
    selectedPackage=null;
    localStorage.removeItem('everyEventBooking');
    localStorage.removeItem('everyEventSelectedPackage');
    saveCart();
    showMessage(name+' added to your booking cart.');
}

function selectPackage(name,price){
    selectedPackage={name,price,quantity:1};
    localStorage.setItem('everyEventSelectedPackage',JSON.stringify(selectedPackage));
    showMessage(name+' selected for your custom quote.');
}

function saveCart(){
    localStorage.setItem('everyEventCart',JSON.stringify(bookingCart));
    renderCart();
}

function renderCart(){
    const count=bookingCart.reduce((total,item)=>total+item.quantity,0);
    const total=bookingCart.reduce((sum,item)=>sum+(item.price*item.quantity),0);
    const cartCount=document.getElementById('cartCount');
    const bookingCount=document.getElementById('bookingCount');
    const cartItems=document.getElementById('cartItems');
    const cartTotal=document.getElementById('cartTotal');
    if(!cartCount||!cartItems||!cartTotal){return}
    cartCount.textContent=count;
    if(bookingCount){bookingCount.textContent=bookingDetails.length}
    cartTotal.textContent='₱'+total.toLocaleString();
    cartItems.innerHTML=bookingCart.length?bookingCart.map((item,index)=>`
        <div class="cart-item">
            <div><strong>${item.name}</strong><span>₱${item.price.toLocaleString()} x ${item.quantity}</span></div>
            <button class="remove-cart-item" type="button" onclick="removeFromCart(${index})">REMOVE</button>
        </div>`).join(''):'<p class="empty-cart">Your booking cart is empty.</p>';
}

function openCart(){
    renderCart();
    openModal('cartModal');
}

function removeFromCart(index){
    bookingCart.splice(index,1);
    saveCart();
}

function proceedToBooking(){
    closeModal('cartModal');
    showPage(null,'booking');
}

function openBookingSummary(){
    renderBookingSummary();
    openModal('bookingSummaryModal');
}

function escapeHtml(value){
    return String(value).replace(/[&<>'"]/g,character=>({
        '&':'&amp;',
        '<':'&lt;',
        '>':'&gt;',
        "'":'&#39;',
        '"':'&quot;'
    }[character]));
}

function renderBookingSummary(){
    const summary=document.getElementById('bookingSummary');
    if(!summary){return}
    const packages=bookingCart.length?bookingCart.map(item=>`${escapeHtml(item.name)} (${item.quantity} x ₱${item.price.toLocaleString()}/person)`).join('<br>'):selectedPackage?`${escapeHtml(selectedPackage.name)} (₱${selectedPackage.price.toLocaleString()}/person)`:'No package selected yet.';
    if(!bookingDetails.length){
        summary.innerHTML=`<p class="summary-empty">Complete a booking to see customer and event details here.</p><p class="summary-packages"><strong>Selected packages:</strong><br>${packages}</p>`;
        return;
    }
    summary.innerHTML=bookingDetails.map((booking,index)=>`<article class="booking-record">
        <h3>BOOKING ${index+1}</h3>
        <div class="summary-row"><strong>Customer</strong><span>${escapeHtml(booking.name)}</span></div>
        <div class="summary-row"><strong>Email</strong><span>${escapeHtml(booking.email)}</span></div>
        <div class="summary-row"><strong>Event date</strong><span>${escapeHtml(booking.date)}</span></div>
        <div class="summary-row"><strong>Event time</strong><span>${escapeHtml(booking.time)}</span></div>
        <div class="summary-row"><strong>Guests</strong><span>${escapeHtml(booking.guests)}</span></div>
        <p class="summary-packages"><strong>Package:</strong><br>${booking.packages}</p>
        <p class="summary-total">TOTAL TO PAY: ₱${booking.total.toLocaleString()}</p>
    </article>`).join('');
}

function showPage(event,sectionId){
    if(event){event.preventDefault()}
    document.body.className='page-'+sectionId;
    document.getElementById(sectionId).scrollIntoView({behavior:'smooth'});
}

function goToBooking(event,name,price){
    if(name&&price){
        selectPackage(name,price);
    }
    showPage(event,'booking');
}
function showMessage(message){
    const registerModal=document.getElementById('registerModal');
    if(registerModal){registerModal.classList.remove('show')}
    const toast=document.getElementById('toast');
    toast.textContent=message;toast.style.display='block';
    setTimeout(()=>toast.style.display='none',3000);
}
function submitBooking(event){
    event.preventDefault();
    const selectedItems=bookingCart.length?bookingCart:(selectedPackage?[selectedPackage]:[]);
    if(!selectedItems.length){
        showMessage('Please add a package to your cart first.');
        return;
    }
    const name=document.getElementById('bookingName').value.trim();
    const email=document.getElementById('bookingEmail').value.trim();
    const date=document.getElementById('bookingDate').value;
    const time=document.getElementById('bookingTime').value;
    const guests=Number(document.getElementById('bookingGuests').value);
    const total=selectedItems.reduce((sum,item)=>sum+(item.price*item.quantity*guests),0);
    const newBooking={
        name,
        email,
        date,
        time,
        guests,
        packages:selectedItems.map(item=>`${item.name} (${item.quantity} x ₱${item.price.toLocaleString()}/person)`).join('<br>'),
        total
    };
    bookingDetails.push(newBooking);
    localStorage.setItem('everyEventBooking',JSON.stringify(bookingDetails));
    bookingCart=[];
    selectedPackage=null;
    localStorage.removeItem('everyEventCart');
    localStorage.removeItem('everyEventSelectedPackage');
    document.getElementById('bookingForm').reset();
    renderCart();
    renderBookingSummary();
    showMessage('Thank you! Your booking request has been received.');
}
window.addEventListener('click',function(e){
    document.querySelectorAll('.modal').forEach(modal=>{if(e.target===modal)modal.classList.remove('show')})
});
document.addEventListener('DOMContentLoaded',function(){
    if(loggedInUser){
        document.body.classList.remove('auth-required');
    }
    showPage(null,'home');
    renderCart();
    renderBookingSummary();
    renderAuthState();
});
