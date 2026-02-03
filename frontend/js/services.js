function showDetails(service) {
  const details = {
    buy: "We curate high-quality audio products with verified specs and eco-conscious packaging. Every purchase supports circular commerce.",
    sell: "List your used audio gear with our easy-to-use interface. We verify listings and help you reach buyers who value sustainability.",
    repair: "Our certified technicians offer diagnostics and repairs using upcycled components whenever possible. Save money and reduce e-waste.",
    exchange: "Trade in your old gear for something new or different. Our platform matches you with users looking to swap responsibly.",
    learn: "Access repair tutorials, tech guides, and community forums. Learn how to fix, upgrade, and understand your devices better."
  };

  const box = document.getElementById("service-details");
  box.innerHTML = `<strong>${service.charAt(0).toUpperCase() + service.slice(1)}:</strong> ${details[service]}`;
  box.style.display = "block";
}