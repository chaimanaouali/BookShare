// JavaScript code to test the API endpoints
// Open browser console (F12) and paste this code

console.log('🧪 Testing Reading Personality API...');

// Test 1: Get personality data
fetch('/reading-personality/data')
  .then(response => response.json())
  .then(data => {
    console.log('✅ Personality Data:', data);
  })
  .catch(error => {
    console.error('❌ Error getting data:', error);
  });

// Test 2: Generate personality (if you have enough borrowing history)
fetch('/reading-personality/generate', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  }
})
.then(response => response.json())
.then(data => {
  console.log('✅ Generate Response:', data);
})
.catch(error => {
  console.error('❌ Error generating:', error);
});

// Test 3: Check if user has enough history
fetch('/reading-personality/data')
  .then(response => response.json())
  .then(data => {
    if (data.has_enough_history) {
      console.log('✅ User has enough borrowing history for personality generation');
    } else {
      console.log('⚠️ User needs more borrowing history (at least 3 books)');
    }
  });
