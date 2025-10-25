// Local storage management for admin data

export interface Answer {
  id: string;
  text: string;
  isCorrect: boolean;
}

export interface Question {
  id: string;
  categoryId: string;
  difficultyId: string;
  question: string;
  answers: Answer[];
  hint?: string;
  createdAt: string;
}

export interface Category {
  id: string;
  name: string;
  description?: string;
}

export interface Difficulty {
  id: string;
  name: string;
  prize: number;
  order: number;
}

const STORAGE_KEYS = {
  QUESTIONS: 'game_questions',
  CATEGORIES: 'game_categories',
  DIFFICULTIES: 'game_difficulties',
  ADMIN_TOKEN: 'admin_token',
};

// Initialize default data
const DEFAULT_CATEGORIES: Category[] = [
  { id: '1', name: 'Geografia', description: 'Perguntas sobre geografia mundial' },
  { id: '2', name: 'História', description: 'Perguntas sobre história' },
  { id: '3', name: 'Ciências', description: 'Perguntas sobre ciências' },
  { id: '4', name: 'Entretenimento', description: 'Perguntas sobre cultura pop' },
];

const DEFAULT_DIFFICULTIES: Difficulty[] = [
  { id: '1', name: 'Nível 1', prize: 1000, order: 1 },
  { id: '2', name: 'Nível 2', prize: 2000, order: 2 },
  { id: '3', name: 'Nível 3', prize: 3000, order: 3 },
  { id: '4', name: 'Nível 4', prize: 5000, order: 4 },
  { id: '5', name: 'Nível 5', prize: 10000, order: 5 },
  { id: '6', name: 'Nível 6', prize: 20000, order: 6 },
  { id: '7', name: 'Nível 7', prize: 30000, order: 7 },
  { id: '8', name: 'Nível 8', prize: 40000, order: 8 },
  { id: '9', name: 'Nível 9', prize: 50000, order: 9 },
  { id: '10', name: 'Nível 10', prize: 100000, order: 10 },
  { id: '11', name: 'Nível 11', prize: 200000, order: 11 },
  { id: '12', name: 'Nível 12', prize: 300000, order: 12 },
  { id: '13', name: 'Nível 13', prize: 400000, order: 13 },
  { id: '14', name: 'Nível 14', prize: 500000, order: 14 },
  { id: '15', name: 'Nível 15', prize: 1000000, order: 15 },
];

// Questions CRUD
export const getQuestions = (): Question[] => {
  const data = localStorage.getItem(STORAGE_KEYS.QUESTIONS);
  return data ? JSON.parse(data) : [];
};

export const getQuestion = (id: string): Question | undefined => {
  return getQuestions().find(q => q.id === id);
};

export const saveQuestion = (question: Omit<Question, 'id' | 'createdAt'>): Question => {
  const questions = getQuestions();
  const newQuestion: Question = {
    ...question,
    id: Date.now().toString(),
    createdAt: new Date().toISOString(),
  };
  questions.push(newQuestion);
  localStorage.setItem(STORAGE_KEYS.QUESTIONS, JSON.stringify(questions));
  return newQuestion;
};

export const updateQuestion = (id: string, data: Partial<Question>): Question | null => {
  const questions = getQuestions();
  const index = questions.findIndex(q => q.id === id);
  if (index === -1) return null;
  
  questions[index] = { ...questions[index], ...data };
  localStorage.setItem(STORAGE_KEYS.QUESTIONS, JSON.stringify(questions));
  return questions[index];
};

export const deleteQuestion = (id: string): boolean => {
  const questions = getQuestions();
  const filtered = questions.filter(q => q.id !== id);
  if (filtered.length === questions.length) return false;
  
  localStorage.setItem(STORAGE_KEYS.QUESTIONS, JSON.stringify(filtered));
  return true;
};

// Categories CRUD
export const getCategories = (): Category[] => {
  const data = localStorage.getItem(STORAGE_KEYS.CATEGORIES);
  if (!data) {
    localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(DEFAULT_CATEGORIES));
    return DEFAULT_CATEGORIES;
  }
  return JSON.parse(data);
};

export const saveCategory = (category: Omit<Category, 'id'>): Category => {
  const categories = getCategories();
  const newCategory: Category = {
    ...category,
    id: Date.now().toString(),
  };
  categories.push(newCategory);
  localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(categories));
  return newCategory;
};

export const updateCategory = (id: string, data: Partial<Category>): Category | null => {
  const categories = getCategories();
  const index = categories.findIndex(c => c.id === id);
  if (index === -1) return null;
  
  categories[index] = { ...categories[index], ...data };
  localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(categories));
  return categories[index];
};

export const deleteCategory = (id: string): boolean => {
  const categories = getCategories();
  const filtered = categories.filter(c => c.id !== id);
  if (filtered.length === categories.length) return false;
  
  localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(filtered));
  return true;
};

// Difficulties CRUD
export const getDifficulties = (): Difficulty[] => {
  const data = localStorage.getItem(STORAGE_KEYS.DIFFICULTIES);
  if (!data) {
    localStorage.setItem(STORAGE_KEYS.DIFFICULTIES, JSON.stringify(DEFAULT_DIFFICULTIES));
    return DEFAULT_DIFFICULTIES;
  }
  return JSON.parse(data);
};

export const saveDifficulty = (difficulty: Omit<Difficulty, 'id'>): Difficulty => {
  const difficulties = getDifficulties();
  const newDifficulty: Difficulty = {
    ...difficulty,
    id: Date.now().toString(),
  };
  difficulties.push(newDifficulty);
  difficulties.sort((a, b) => a.order - b.order);
  localStorage.setItem(STORAGE_KEYS.DIFFICULTIES, JSON.stringify(difficulties));
  return newDifficulty;
};

export const updateDifficulty = (id: string, data: Partial<Difficulty>): Difficulty | null => {
  const difficulties = getDifficulties();
  const index = difficulties.findIndex(d => d.id === id);
  if (index === -1) return null;
  
  difficulties[index] = { ...difficulties[index], ...data };
  difficulties.sort((a, b) => a.order - b.order);
  localStorage.setItem(STORAGE_KEYS.DIFFICULTIES, JSON.stringify(difficulties));
  return difficulties[index];
};

export const deleteDifficulty = (id: string): boolean => {
  const difficulties = getDifficulties();
  const filtered = difficulties.filter(d => d.id !== id);
  if (filtered.length === difficulties.length) return false;
  
  localStorage.setItem(STORAGE_KEYS.DIFFICULTIES, JSON.stringify(filtered));
  return true;
};

// Admin auth
export const loginAdmin = (password: string): boolean => {
  // Simple admin check - in production, this should be server-side
  if (password === 'admin123') {
    localStorage.setItem(STORAGE_KEYS.ADMIN_TOKEN, 'authenticated');
    return true;
  }
  return false;
};

export const logoutAdmin = (): void => {
  localStorage.removeItem(STORAGE_KEYS.ADMIN_TOKEN);
};

export const isAdminAuthenticated = (): boolean => {
  return localStorage.getItem(STORAGE_KEYS.ADMIN_TOKEN) === 'authenticated';
};
