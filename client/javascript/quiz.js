(() => {
    /**
     * Find next step and swap active state
     * If nextStep is result, trigger ajax call to retrieve results
     * 
     */
    const goToNextStep = (stepOrQuiz, direction) => {
        const step = stepOrQuiz.hasAttribute('data-step') ? stepOrQuiz : findCurrentStep(stepOrQuiz);
        const quiz = stepOrQuiz.hasAttribute('data-quiz') ? stepOrQuiz : findQuiz(step);
        const currentStep = findCurrentStep(quiz);
        const nextStep =  currentStep ? (direction === 'back' ? currentStep.previousElementSibling : currentStep.nextElementSibling)  : null;
        if (nextStep) {
            activateStep(nextStep, quiz);
    
            const isResultStep = nextStep.dataset.stepType === 'result'; 
            if (isResultStep === true) {
                nextStep.classList.add('loading');
                fetchResultForStep(nextStep, quiz);
            }
        }
    }
    
    /**
     * Step all steps to inactive then
     * activate a step
     * 
     */
    const activateStep = (step, quiz) => {
        const steps = quiz.querySelectorAll('[data-step]');
        steps.forEach((item) => {
            item.classList.remove('active');
            item.setAttribute('aria-hidden', 'true');
        });
    
        step.classList.add('active');
        step.setAttribute('aria-hidden', 'false');
    
        // Update progress
        quiz.setAttribute('data-current-step', step.getAttribute('data-step'));
    
        const progress = quiz.querySelector('progress');
        if (progress) {
            updateProgress(step, progress);
        }
    }
    
    /**
     * If a <progress> is found
     * update value to reflect currently visible step
     * NOTE: step count should be 0 based
     * 
     */
    const updateProgress = (activeStep, progress) => {
        if (progress) {
            const value = parseInt(activeStep.dataset.step) - 1;
            progress.setAttribute('value', value);
        }
    }
    
    /**
     * Find current step
     * 
     */
    const findCurrentStep = (quiz) => {
        return quiz.querySelector('.quiz-step.active');
    }
    
    const findStep = (quiz, index) => {
        return quiz.querySelector(`[data-step="${index}"`);
    }
    
    const findQuiz = (step) => {
        return step.closest('[data-quiz]');
    }
    
    /**
     * Use fetch API to submit form for a specific result step
     * Required form action to be set to an ajax endpoint
     * and step content outermost wrapper to have attribute data-result-step="$ID"
     * 
     */
    const fetchResultForStep = async (step, quiz) => {
        const stepId = step.dataset.stepId;
        const form = step.closest('form');
        const data = new FormData(form);
        data.append('resultStepId', stepId);
    
        const response = await fetch(form.getAttribute('action'), {
            method: 'post',
            body: new URLSearchParams(data),
            headers: {
                'ContentType': 'text/html',
                'x-requested-with': 'XMLHttpRequest'
            }
        });
    
        if (response.ok) {
            const feedback = await response.text();
            step.innerHTML = feedback; // TODO: sanitize html
        } else {
            step.innerHTML = 'Something went wrong. Please try again.';
        }
    
        step.classList.remove('loading');
        bindEvents(step); // rebind events for result steps
    }
    
    /**
     * Reset form fields, empty results
     * and navigate to first step
     * 
     */
    const resetQuiz = (step) => {
        const quiz = findQuiz(step);
        // Empty results
        const results = quiz.querySelectorAll('[data-step-type="result"]');
        results.forEach((item) => {
            item.innerHTML = '';
        });
        // Reset form
        const form = quiz.querySelector('form');
        form.reset();
        // Activate first step
        const firstStep = findStep(quiz, 1);
        activateStep(firstStep, quiz);
    }
    
    /**
     * Bind events
     */
    const bindEvents = (step) => {
        step.querySelectorAll('[data-next]').forEach((next) => {
            next.addEventListener('click', () => {
                goToNextStep(step);
            });
        });
    
        step.querySelectorAll('[data-reset]').forEach((reset) => {
            reset.addEventListener('click', () => {
                resetQuiz(step);
            });
        });
    }
    
    document.addEventListener("DOMContentLoaded", () => {
        const steps = document.querySelectorAll('[data-step]');
        if (steps.length === 0) return;
    
        steps.forEach((step) => {
            bindEvents(step);
        })
    
        // Back button are outside of steps so need to be bound only once
        const backButtons = document.querySelectorAll('[data-quiz] > [data-back]');
        backButtons.forEach((back) => {
            back.addEventListener('click', () => {
                goToNextStep(back.closest('[data-quiz]'), 'back');
            });
        });
      });
    })();