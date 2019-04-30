import {Routes} from '@angular/router';
import {QuizComponent} from './quiz/quiz.component';
import {ResultComponent} from './result/result.component';

export const appRoutes: Routes = [
    {path: '', component: QuizComponent},
    {path: 'result', component: ResultComponent}
];
