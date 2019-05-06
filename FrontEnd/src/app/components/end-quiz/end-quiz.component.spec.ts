import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EndQuizComponent } from './end-quiz.component';

describe('EndQuizComponent', () => {
  let component: EndQuizComponent;
  let fixture: ComponentFixture<EndQuizComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EndQuizComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EndQuizComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
