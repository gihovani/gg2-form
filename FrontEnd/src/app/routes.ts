import {Routes} from '@angular/router';
import {QuizComponent} from './components/quiz/quiz.component';
import {ResultComponent} from './components/result/result.component';
import {EndQuizComponent} from './components/end-quiz/end-quiz.component';

export const appRoutes: Routes = [
  {path: '', component: QuizComponent},
  {path: 'end-quiz', component: EndQuizComponent},
  {path: 'result', component: ResultComponent}
];
