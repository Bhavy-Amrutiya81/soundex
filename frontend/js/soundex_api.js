// Soundex API Helper Functions
const API_BASE_URL = 'http://localhost/soundex/api.php';

class SoundexAPI {
    static async makeRequest(action, data = {}, method = 'POST') {
        try {
            const response = await fetch(`${API_BASE_URL}?action=${action}`, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            return { success: false, message: 'Network error occurred' };
        }
    }
    
    // User Management
    static async registerUser(userData) {
        return await this.makeRequest('register', userData);
    }
    
    static async loginUser(username, password) {
        const guestSessionId = this.getSessionId(); // Get the guest session ID before login
        return await this.makeRequest('login', { 
            username, 
            password,
            guest_session_id: guestSessionId
        });
    }
    
    static async validateSession(sessionToken) {
        return await this.makeRequest('validate_session', { session_token: sessionToken });
    }
    
    static async logoutUser(sessionToken) {
        return await this.makeRequest('logout', { session_token: sessionToken });
    }
    
    // Product Management
    static async getProducts() {
        return await this.makeRequest('get_products', {}, 'GET');
    }
    
    static async addToCart(productId, quantity = 1, userId = null) {
        const sessionId = this.getSessionId();
        return await this.makeRequest('add_to_cart', { 
            user_id: userId, 
            session_id: sessionId, 
            product_id: productId, 
            quantity 
        });
    }
    
    static async getCart(userId = null) {
        const sessionId = this.getSessionId();
        return await this.makeRequest('get_cart', { 
            user_id: userId, 
            session_id: sessionId 
        });
    }
    
    static async removeFromCart(cartId, userId = null) {
        const sessionId = this.getSessionId();
        return await this.makeRequest('remove_from_cart', { 
            cart_id: cartId, 
            user_id: userId, 
            session_id: sessionId 
        });
    }
    
    static async createOrder(orderData, userId = null) {
        if (!userId) {
            // Redirect to login if user is not logged in
            alert('Please sign in to complete your purchase.');
            window.location.href = 'pages/signup.html'; // Redirect to login/signup page
            return { success: false, message: 'Authentication required to place an order' };
        }
        
        const sessionId = this.getSessionId();
        return await this.makeRequest('create_order', { 
            ...orderData,
            user_id: userId,
            session_id: sessionId
        });
    }
    
    // Service Booking
    static async bookService(serviceData, userId = null) {
        return await this.makeRequest('book_service', { 
            ...serviceData,
            user_id: userId 
        });
    }
    
    static async getServices() {
        return await this.makeRequest('get_services', {}, 'GET');
    }
    
    // Application Management
    static async submitApplication(formData, files = []) {
        const formDataObj = new FormData();
        
        // Add form data
        Object.keys(formData).forEach(key => {
            formDataObj.append(key, formData[key]);
        });
        
        // Add files
        files.forEach((file, index) => {
            formDataObj.append(`photo_${index}`, file);
        });
        
        try {
            const response = await fetch(`${API_BASE_URL}?action=submit_application`, {
                method: 'POST',
                body: formDataObj
            });
            
            return await response.json();
        } catch (error) {
            console.error('Application submission error:', error);
            return { success: false, message: 'Failed to submit application' };
        }
    }
    
    // Contact Management
    static async sendMessage(name, email, subject, message) {
        return await this.makeRequest('send_message', { name, email, subject, message });
    }
    
    static async submitFAQFeedback(faqId, helpful, userId = null) {
        return await this.makeRequest('submit_faq_feedback', { 
            faq_id: faqId, 
            helpful, 
            user_id: userId 
        });
    }
    
    // Utility Functions
    static getSessionId() {
        let sessionId = localStorage.getItem('session_id');
        if (!sessionId) {
            sessionId = 'sess_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('session_id', sessionId);
        }
        return sessionId;
    }
    
    static setAuthToken(token) {
        localStorage.setItem('auth_token', token);
    }
    
    static getAuthToken() {
        return localStorage.getItem('auth_token');
    }
    
    static clearAuth() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_data');
    }
    
    static setUser(user) {
        localStorage.setItem('user_data', JSON.stringify(user));
    }
    
    static getUser() {
        const userData = localStorage.getItem('user_data');
        return userData ? JSON.parse(userData) : null;
    }
    
    static isLoggedIn() {
        return !!this.getAuthToken() && !!this.getUser();
    }
    
    // Get current user ID
    static getCurrentUserId() {
        const user = this.getUser();
        return user ? user.id : null;
    }
}

// Enhanced form handlers for existing HTML forms

// Login Form Handler
document.addEventListener('DOMContentLoaded', function() {
    // Handle login forms
    const loginForms = document.querySelectorAll('form.login-form');
    loginForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = form.querySelector('input[type="text"]').value;
            const password = form.querySelector('input[type="password"]').value;
            
            const result = await SoundexAPI.loginUser(username, password);
            
            if (result.success) {
                SoundexAPI.setAuthToken(result.session_token);
                SoundexAPI.setUser(result.user);
                alert('Login successful!');
                
                // Redirect back to where they came from or to homepage
                const returnUrl = sessionStorage.getItem('return_url') || 'pages/home.html';
                sessionStorage.removeItem('return_url'); // Clean up
                window.location.href = returnUrl;
            } else {
                alert('Login failed: ' + result.message);
            }
        });
    });
    
    // Handle signup form
    const signupForm = document.querySelector('form[action="redirectToPage"]');
    if (signupForm) {
        signupForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(signupForm);
            const userData = {
                username: formData.get('username'),
                email: formData.get('email'),
                password: formData.get('password'),
                confirm_password: formData.get('confirm_password')
            };
            
            if (userData.password !== userData.confirm_password) {
                alert('Passwords do not match!');
                return;
            }
            
            const result = await SoundexAPI.registerUser(userData);
            
            if (result.success) {
                alert('Account created successfully!');
                window.location.href = 'pages/signup.html'; // Redirect to login
            } else {
                alert('Registration failed: ' + result.message);
            }
        });
    }
    
    // Handle internship application forms
    const internshipForms = document.querySelectorAll('#scholarshipForm, #deviceForm');
    internshipForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const jsonData = {};
            
            // Convert FormData to JSON
            for (let [key, value] of formData.entries()) {
                if (key !== 'photos') { // Handle file uploads separately
                    jsonData[key] = value;
                }
            }
            
            // Handle checkbox arrays
            const checkboxes = form.querySelectorAll('input[type="checkbox"]:checked');
            const problems = Array.from(checkboxes).map(cb => cb.value);
            jsonData.problems = problems;
            
            // Handle file uploads
            const photoFiles = form.querySelector('#photos')?.files || [];
            
            const result = await SoundexAPI.submitApplication(jsonData, Array.from(photoFiles));
            
            if (result.success) {
                alert('Application submitted successfully! Application ID: ' + result.application_id);
                form.reset();
            } else {
                alert('Submission failed: ' + result.message);
            }
        });
    });
    
    // Handle contact forms
    const contactForms = document.querySelectorAll('.contact-form');
    contactForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const name = form.querySelector('input[type="text"]').value;
            const email = form.querySelector('input[type="email"]').value;
            const message = form.querySelector('textarea').value;
            
            const result = await SoundexAPI.sendMessage(name, email, '', message);
            
            if (result.success) {
                alert('Message sent successfully!');
                form.reset();
            } else {
                alert('Failed to send message: ' + result.message);
            }
        });
    });
});

// Export for use in other scripts
window.SoundexAPI = SoundexAPI;