// public/assets/script.js
function field(name, placeholder){ return `<input name="${name}" placeholder="${placeholder}">`; }

function addEdu(){
  document.getElementById('edu-list').insertAdjacentHTML('beforeend', `
  <div class="edu-item">
    ${field('edu_institution[]','Institution')}
    ${field('edu_degree[]','Degree')}
    <label>Start<input type="date" name="edu_start[]"></label>
    <label>End<input type="date" name="edu_end[]"></label>
    <label>Description<textarea name="edu_desc[]" placeholder="Relevant courses, honors"></textarea></label>
    <label>Institution email<input name="edu_email[]" type="email" placeholder="registrar@university.ac.tz"></label>
    <hr>
  </div>`);
}

function addWork(){
  document.getElementById('work-list').insertAdjacentHTML('beforeend', `
  <div class="work-item">
    ${field('work_company[]','Company')}
    ${field('work_role[]','Role')}
    <label>Start<input type="date" name="work_start[]"></label>
    <label>End<input type="date" name="work_end[]"></label>
    <label>Responsibilities<textarea name="work_resp[]" placeholder="3–5 bullet points"></textarea></label>
    <label>HR email<input name="work_email[]" type="email" placeholder="hr@company.com"></label>
    <hr>
  </div>`);
}

function addSkill(){
  document.getElementById('skills-list').insertAdjacentHTML('beforeend', `
  <div class="skill-item">
    <input name="skill_name[]" placeholder="Skill">
    <select name="skill_level[]">
      <option>Beginner</option><option selected>Intermediate</option>
      <option>Advanced</option><option>Expert</option>
    </select>
  </div>`);
}

function addRef(){
  document.getElementById('ref-list').insertAdjacentHTML('beforeend', `
  <div class="ref-item">
    ${field('ref_name[]','Name')}
    <input name="ref_email[]" type="email" placeholder="Email">
    ${field('ref_relation[]','Relation')}
    ${field('ref_phone[]','Phone')}
    <hr>
  </div>`);
}

// Add one default block only if there are no existing items (so saved rows remain)
document.addEventListener('DOMContentLoaded', () => {
  if (!document.querySelector('#edu-list .edu-item')) addEdu();
  if (!document.querySelector('#work-list .work-item')) addWork();
  if (!document.querySelector('#skills-list .skill-item')) addSkill();
  if (!document.querySelector('#ref-list .ref-item')) addRef();
});