function showUpdateForm(userId) {
  const formContainer = document.getElementById(`user-update-form-${userId}`);

  // Vérifier si le formulaire est déjà visible
  if (formContainer.style.display === "block") {
      // Si le formulaire est déjà visible, on le cache
      formContainer.style.display = "none";
  } else {
      formContainer.style.display = "block";
  }
}

function hideUpdateForm(userId) {
  const formContainer = document.getElementById(`user-update-form-${userId}`);
  formContainer.style.display = "none"; 
}

function showPostUpdateForm(postId) {
  const formContainer = document.getElementById(`update-form-post-${postId}`);

  if (formContainer.style.display === "block") {
      formContainer.style.display = "none";
  } else {
      formContainer.style.display = "block";
  }
}

function hidePostUpdateForm(postId) {
  const formContainer = document.getElementById(`update-form-post-${postId}`);
  formContainer.style.display = "none";
}

function toggleCommentForm(commentId) {
  console.log(`toggleCommentForm called with commentId: ${commentId}`);
  const formContainer = document.getElementById(`update-form-${commentId}`);
  const btnComment = document.getElementById(`btn-comment-${commentId}`);

  if (formContainer) {
    console.log(`Form container found for commentId: ${commentId}`);
    console.log(`Current display style: ${formContainer.style.display}`);
    if (formContainer.style.display === "block" || formContainer.style.display === "") {
        formContainer.style.display = "none";
        btnComment.textContent = "Modifier";
    } else {
      formContainer.style.display = "block";
      btnComment.textContent = "Annuler";
    }
    console.log(`New display style: ${formContainer.style.display}`);
  } else {
    console.log(`Form container not found for commentId: ${commentId}`);
  }
}

function hideCommentForm(commentId) {
  console.log(`hideCommentForm called with commentId: ${commentId}`);
  const formContainer = document.getElementById(`update-form-${commentId}`);
  const btnComment = document.getElementById(`btn-comment-${commentId}`);

  if (formContainer) {
    formContainer.style.display = "none";
    btnComment.textContent = "Modifier";
  } else {
    console.log(`Form container not found for commentId: ${commentId}`);
  }
}

// Code pour limiter le nombre de char et cacher le surplus du post
// Limiter le nombre de caractères et masquer le reste
const textElements = document.querySelectorAll(".post-content");

textElements.forEach((text) => {
  if (text.textContent.length > 150) {
    const shortText = text.textContent.slice(0, 150) + "...";
    text.textContent = shortText;
  }
});

// Cacher les commentaires par défaut
const toggleBtn = document.getElementById("toggle-comments-btn");
const commentsSection = document.getElementById("comments-section");

toggleBtn.addEventListener("click", function () {
  if (commentsSection.style.display === "none") {
    commentsSection.style.display = "block";
    toggleBtn.textContent = "Masquer les commentaires";
  } else {
    commentsSection.style.display = "none";
    toggleBtn.textContent = "Voir les commentaires";
  }
});